<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EMCustomer;
use App\Models\EMCustomerChild;
use App\Models\EMCustomerChildParent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ZipArchive;

class ParentsBookingController extends Controller
{
    public function index()
    {
        $savedChildren = EMCustomerChild::with(['parentRelations.customer'])->where('is_active', 1)->orderByDesc('id')->get();
        return view('admin.parents_booking.index', [
            'headers' => session('member_import_headers', []),'rows' => session('member_import_rows', []),
            'uploadedPath' => session('member_import_path'),'originalFileName' => session('member_import_name'),
            'savedChildren' => $savedChildren,
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate(['excel_file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240']);
        $file = $request->file('excel_file');$extension = strtolower($file->getClientOriginalExtension());
        if ($extension === 'xls' && !class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
            return back()->with('error', 'Legacy .xls files require PhpSpreadsheet. Please save the file as .xlsx or .csv and upload again.');
        }
        $fileName = time().'_'.Str::random(10).'.'.$extension;$stored = $file->storeAs('imports/parents_booking', $fileName);
        try { [$headers, $rows] = $this->readSpreadsheet(storage_path('app/'.$stored), $extension); }
        catch (\Throwable $e) { return back()->with('error', 'Could not read the spreadsheet: '.$e->getMessage()); }
        return redirect()->route('admin.parents.booking.index')->with([
            'member_import_headers'=>$headers,'member_import_rows'=>$rows,'member_import_path'=>$stored,
            'member_import_name'=>$file->getClientOriginalName(),'success'=>count($rows).' row(s) loaded. Review the preview, then import.',
        ]);
    }

    public function import(Request $request)
    {
        $headers=$request->input('headers',[]);$rows=$request->input('rows',[]);
        if(!is_array($headers)||!is_array($rows)||empty($rows))return back()->with('error','No spreadsheet rows were supplied for import.');
        $imported=0;$skipped=0;DB::beginTransaction();
        try{
            foreach($rows as $row){
                $assoc=[];foreach($headers as $i=>$header){$key=trim((string)$header)?:'Column '.($i+1);$assoc[$key]=$row[$i]??null;}
                if(collect($assoc)->filter(fn($v)=>trim((string)$v)!=='')->isEmpty())continue;
                $parent1=$this->saveImportParent($assoc,'parent_one',1,null);$parent2=$this->saveImportParent($assoc,'parent_two',2,$parent1?->id);
                $first=$this->importValue($assoc,['first_name','First Name','player_first','Player First','Player First Name','child_first_name','Child First Name']);
                $last=$this->importValue($assoc,['last_name','Last Name','player_last','Player Last','Player Last Name','child_last_name','Child Last Name']);
                if(!$first&&!$last){$skipped++;continue;}
                $classYear=$this->importValue($assoc,['class_year','Class Year','grade','Grade','grad_year','Grad Year']);
                $childQuery=EMCustomerChild::where('first_name',$first)->where('last_name',$last);if($classYear)$childQuery->where('class_year',$classYear);$child=$childQuery->first()?:new EMCustomerChild();
                $child->fill(['first_name'=>$first,'last_name'=>$last,'team'=>$this->importValue($assoc,['team','Team']),'spring_team'=>$this->importValue($assoc,['spring_team','Spring Team']),'position'=>$this->importValue($assoc,['position','Position','positions','Positions']),'class_year'=>$classYear,'birthdate'=>$this->parseDate($this->importValue($assoc,['birthdate','Birthdate','Birth Date','DOB','dob'])),'is_active'=>1])->save();
                $this->attachParent($child,$parent1,true);$this->attachParent($child,$parent2,false);$imported++;
            }
            DB::commit();
        }catch(\Throwable $e){DB::rollBack();return back()->with('error','Import failed: '.$e->getMessage());}
        return redirect()->route('admin.parents.booking.index')->with('success',"{$imported} member(s) imported successfully. Skipped: {$skipped}.");
    }

    public function checkParentEmail(Request $request)
    {
        $request->validate(['email'=>'required|email|max:190']);$customer=EMCustomer::whereRaw('LOWER(email) = ?',[strtolower(trim($request->email))])->first();
        return response()->json(['exists'=>(bool)$customer,'customer'=>$customer?['id'=>$customer->id,'first_name'=>$customer->first_name,'last_name'=>$customer->last_name,'email'=>$customer->email,'phone'=>$customer->phone]:null]);
    }

    public function storeChild(Request $request)
    {
        $data=$this->validateMember($request);DB::beginTransaction();
        try{$parent1=$this->saveAdminParent($data,'parent_one',1,null);if(!$parent1)throw new \RuntimeException('Parent 1 email is required.');$parent2=$this->saveAdminParent($data,'parent_two',2,$parent1->id);$child=EMCustomerChild::create($this->memberData($data));$this->attachParent($child,$parent1,true);$this->attachParent($child,$parent2,false);DB::commit();return back()->with('success','Member added successfully.');}
        catch(\Throwable $e){DB::rollBack();return back()->withInput()->with('error','Member add failed: '.$e->getMessage());}
    }

    public function updateChild(Request $request, EMCustomerChild $child)
    {
        $data=$this->validateMember($request);DB::beginTransaction();
        try{$parent1=$this->saveAdminParent($data,'parent_one',1,null);if(!$parent1)throw new \RuntimeException('Parent 1 email is required.');$parent2=$this->saveAdminParent($data,'parent_two',2,$parent1->id);$child->update($this->memberData($data));EMCustomerChildParent::where('child_id',$child->id)->delete();$this->attachParent($child,$parent1,true);$this->attachParent($child,$parent2,false);DB::commit();return back()->with('success','Member updated successfully.');}
        catch(\Throwable $e){DB::rollBack();return back()->withInput()->with('error','Member update failed: '.$e->getMessage());}
    }

    public function deleteChild(EMCustomerChild $child)
    {
        DB::transaction(function()use($child){$hasBookings=Schema::hasTable('em_session_bookings')&&DB::table('em_session_bookings')->where('child_id',$child->id)->exists();EMCustomerChildParent::where('child_id',$child->id)->delete();if($hasBookings)$child->update(['is_active'=>0]);else$child->delete();});
        return back()->with('success','Member deleted successfully.');
    }
    public function checkEmail(Request $request){return $this->checkParentEmail($request);}public function destroyChild(EMCustomerChild $child){return $this->deleteChild($child);}

    private function validateMember(Request $request):array
    {
        return $request->validate(['first_name'=>'required|string|max:100','last_name'=>'nullable|string|max:100','team'=>'nullable|string|max:150','spring_team'=>'nullable|string|max:150','grade'=>'nullable|string|max:20','class_year'=>'nullable|string|max:20','birthdate'=>'nullable|string|max:30','positions'=>'nullable|array','positions.*'=>'string|in:Attack,Middie,Defense,Goalie','parent_one_id'=>'nullable|integer','parent_one_first_name'=>'nullable|string|max:190','parent_one_last_name'=>'nullable|string|max:190','parent_one_email'=>'required|email|max:190','parent_one_phone'=>'nullable|string|max:80','parent_two_id'=>'nullable|integer','parent_two_first_name'=>'nullable|string|max:190','parent_two_last_name'=>'nullable|string|max:190','parent_two_email'=>'nullable|email|max:190','parent_two_phone'=>'nullable|string|max:80']);
    }
    private function memberData(array $data):array{$positions=collect($data['positions']??[])->filter()->unique()->values()->implode(', ');return['first_name'=>$data['first_name'],'last_name'=>$data['last_name']??null,'team'=>$data['team']??null,'spring_team'=>$data['spring_team']??null,'position'=>$positions?:null,'class_year'=>$data['class_year']??$data['grade']??null,'birthdate'=>$this->parseDate($data['birthdate']??null),'is_active'=>1];}
    private function saveAdminParent(array $data,string $prefix,int $type,?int $relationalId):?EMCustomer
    {
        $id=!empty($data[$prefix.'_id'])?(int)$data[$prefix.'_id']:null;$email=strtolower(trim((string)($data[$prefix.'_email']??'')));$first=trim((string)($data[$prefix.'_first_name']??''));$last=trim((string)($data[$prefix.'_last_name']??''));$phone=trim((string)($data[$prefix.'_phone']??''));if(!$id&&!$email&&!$first&&!$last&&!$phone)return null;
        $parent=$id?EMCustomer::find($id):null;if(!$parent&&$email)$parent=EMCustomer::whereRaw('LOWER(email)=?',[$email])->first();if(!$parent){if(!$email)return null;$parent=new EMCustomer(['password'=>Hash::make(Str::random(20)),'active'=>1]);}
        $parent->first_name=$first?:$parent->first_name;$parent->last_name=$last?:$parent->last_name;if($email)$parent->email=$email;if($phone!=='')$parent->phone=$phone;if(Schema::hasColumn('em_customers','parent_type'))$parent->parent_type=$type;if(Schema::hasColumn('em_customers','relational_id'))$parent->relational_id=$relationalId;if(Schema::hasColumn('em_customers','active'))$parent->active=1;$parent->save();return $parent;
    }
    private function attachParent(EMCustomerChild $child,?EMCustomer $parent,bool $primary):void{if(!$parent)return;EMCustomerChildParent::updateOrCreate(['customer_id'=>$parent->id,'child_id'=>$child->id],['relationship'=>$primary?'Parent 1':'Parent 2','is_primary'=>$primary?1:0,'can_book'=>1,'can_pay'=>1,'can_pickup'=>0,'notes'=>null]);}
    private function saveImportParent(array $row,string $prefix,int $type,?int $relationalId):?EMCustomer{$pretty=ucfirst(str_replace('_',' ',$prefix));$data=[$prefix.'_first_name'=>$this->importValue($row,[$prefix.'_first_name',$pretty.' First Name']),$prefix.'_last_name'=>$this->importValue($row,[$prefix.'_last_name',$pretty.' Last Name']),$prefix.'_email'=>$this->importValue($row,[$prefix.'_email',$pretty.' Email']),$prefix.'_phone'=>$this->importValue($row,[$prefix.'_phone',$pretty.' Phone'])];return $this->saveAdminParent($data,$prefix,$type,$relationalId);}
    private function importValue(array $row,array $keys){foreach($keys as $key)foreach($row as $rowKey=>$value)if(strtolower(trim((string)$rowKey))===strtolower(trim((string)$key))){$value=trim((string)$value);return($value===''||in_array(strtolower($value),['null','n/a','na','-']))?null:$value;}return null;}
    private function parseDate($value):?string{$value=trim((string)$value);if($value==='')return null;foreach(['m/d/Y','n/j/Y','m-d-Y','n-j-Y','Y-m-d']as$format){$date=\DateTime::createFromFormat('!'.$format,$value);if($date&&$date->format($format)===$value)return$date->format('Y-m-d');}$time=strtotime($value);return$time?date('Y-m-d',$time):null;}
    private function readSpreadsheet(string $path,string $extension):array
    {
        if(class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)){$reader=\PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($path);$reader->setReadDataOnly(false);return$this->normalizeRows($reader->load($path)->getActiveSheet()->toArray('',true,true,false));}
        if(in_array($extension,['csv','txt'])){$data=[];$h=fopen($path,'r');while(($row=fgetcsv($h))!==false)$data[]=$row;fclose($h);return$this->normalizeRows($data);}if($extension==='xlsx')return$this->readXlsxWithoutLibrary($path);throw new \RuntimeException('Unsupported spreadsheet type. Use .xlsx or .csv.');
    }
    private function normalizeRows(array $data):array{if(!$data)return[[],[]];$headers=array_map(fn($v)=>trim((string)$v),array_shift($data));$last=-1;foreach($headers as$i=>$v)if($v!=='')$last=$i;if($last<0)return[[],[]];$headers=array_slice($headers,0,$last+1);$rows=[];foreach($data as$row){$row=array_slice(array_pad($row,count($headers),''),0,count($headers));if(collect($row)->filter(fn($v)=>trim((string)$v)!=='')->isNotEmpty())$rows[]=$row;}return[$headers,$rows];}
    private function readXlsxWithoutLibrary(string $path):array
    {
        if(!class_exists(ZipArchive::class))throw new \RuntimeException('ZipArchive extension is required to read .xlsx files.');$zip=new ZipArchive();if($zip->open($path)!==true)throw new \RuntimeException('Invalid .xlsx file.');$shared=[];$sharedXml=$zip->getFromName('xl/sharedStrings.xml');if($sharedXml){$xml=simplexml_load_string($sharedXml);$xml->registerXPathNamespace('x','http://schemas.openxmlformats.org/spreadsheetml/2006/main');foreach($xml->xpath('//x:si')as$si){$texts=$si->xpath('.//x:t');$shared[]=implode('',array_map(fn($t)=>(string)$t,$texts));}}$sheetXml=$zip->getFromName('xl/worksheets/sheet1.xml');$zip->close();if(!$sheetXml)throw new \RuntimeException('The first worksheet could not be found.');$xml=simplexml_load_string($sheetXml);$xml->registerXPathNamespace('x','http://schemas.openxmlformats.org/spreadsheetml/2006/main');$matrix=[];foreach($xml->xpath('//x:sheetData/x:row')as$row){$values=[];foreach($row->xpath('./x:c')as$cell){$ref=(string)$cell['r'];preg_match('/([A-Z]+)(\d+)/',$ref,$m);$index=$this->columnIndex($m[1]??'A');$type=(string)$cell['t'];$v=$cell->v;$value='';if($type==='s')$value=$shared[(int)$v]??'';elseif($type==='inlineStr'){$ts=$cell->xpath('.//x:t');$value=implode('',array_map(fn($t)=>(string)$t,$ts));}else$value=(string)$v;$values[$index]=$value;}if($values){$max=max(array_keys($values));$out=array_fill(0,$max+1,'');foreach($values as$i=>$v)$out[$i]=$v;$matrix[]=$out;}}return$this->normalizeRows($matrix);
    }
    private function columnIndex(string $letters):int{$n=0;foreach(str_split($letters)as$c)$n=$n*26+(ord($c)-64);return max(0,$n-1);}
}
