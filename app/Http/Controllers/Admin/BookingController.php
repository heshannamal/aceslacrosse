<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EMCustomer;
use App\Models\EMCustomerChild;
use App\Models\EMCustomerChildParent;
use App\Models\EMCustomerCredit;
use App\Models\EMCustomerCreditLog;
use App\Models\EMCustomerPackageCart;
use App\Models\EMSessionBooking;
use App\Models\EMSessionEvent;
use App\Services\Training\TrainingMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    private const ACTIVE_STATUSES = ['booked', 'paid', 'completed'];

    public function index(Request $request)
    {
        $sessionName = trim((string) $request->get('session_name'));
        $sessionDate = trim((string) $request->get('session_date'));
        $search = trim((string) $request->get('search'));
        $query = EMSessionBooking::with(['customer','child','sessionEvent','credit'])->where('status','booked')->orderByDesc('booked_at')->orderByDesc('id');
        if ($sessionName !== '') $query->whereHas('sessionEvent', fn($q) => $q->where('training_type',$sessionName)->orWhere('name',$sessionName));
        if ($sessionDate !== '') $query->whereHas('sessionEvent', fn($q) => $q->whereDate('event_date',$sessionDate));
        if ($search !== '') $query->where(function($q) use ($search) { $q->where('booking_no','like','%'.$search.'%')->orWhereHas('customer', fn($c) => $c->where('first_name','like','%'.$search.'%')->orWhere('last_name','like','%'.$search.'%')->orWhere('email','like','%'.$search.'%')->orWhere('phone','like','%'.$search.'%'))->orWhereHas('child', fn($c) => $c->where('first_name','like','%'.$search.'%')->orWhere('last_name','like','%'.$search.'%')); });
        $bookings=$query->paginate((int)$request->get('per_page',25)?:25)->withQueryString();
        $allSessions=EMSessionEvent::where('is_active',1)->orderByDesc('event_date')->orderByDesc('start_time')->get();
        $sessionTypes=$allSessions->map(fn($s)=>trim((string)($s->training_type?:$s->name)))->filter()->unique()->sort()->values();
        $sessionDates=$allSessions->pluck('event_date')->filter()->map(fn($d)=>$d instanceof \Carbon\Carbon?$d->format('Y-m-d'):date('Y-m-d',strtotime($d)))->unique()->sortDesc()->values();
        $activeCredits=EMCustomerCredit::available()->get()->groupBy('customer_id');
        $customers=EMCustomer::active()->orderBy('first_name')->orderBy('last_name')->get();
        $manualCustomers=$customers->map(function($customer)use($activeCredits){$credits=(int)$activeCredits->get($customer->id,collect())->sum('remaining_classes');return['id'=>$customer->id,'name'=>$customer->full_name,'email'=>$customer->email,'phone'=>$customer->phone,'credits'=>$credits,'disabled'=>$credits<=0];})->values();
        $relations=EMCustomerChildParent::whereIn('customer_id',$customers->pluck('id'))->get();
        $children=EMCustomerChild::whereIn('id',$relations->pluck('child_id')->unique())->where('is_active',1)->get()->keyBy('id');
        $childrenByCustomer=[]; foreach($relations as$rel){$child=$children->get($rel->child_id);if(!$child)continue;$childrenByCustomer[$rel->customer_id][]=['id'=>$child->id,'name'=>$child->full_name,'meta'=>collect([$child->class_year,$child->team,$child->position])->filter()->implode(' · ')];}
        $counts=EMSessionBooking::whereIn('session_event_id',$allSessions->pluck('id'))->whereIn('status',self::ACTIVE_STATUSES)->selectRaw('session_event_id, COUNT(*) total')->groupBy('session_event_id')->pluck('total','session_event_id');
        $manualSessions=$allSessions->map(function($s)use($counts){$booked=(int)($counts[$s->id]??0);$capacity=(int)$s->capacity;$expired=$this->isExpired($s);$full=$capacity>0&&$booked>=$capacity;return['id'=>$s->id,'title'=>$s->training_type?:$s->name,'date'=>$s->event_date?->format('Y-m-d'),'date_label'=>$s->event_date?->format('M d, Y'),'time'=>$this->timeRange($s),'location'=>$s->location,'capacity'=>$capacity,'booked'=>$booked,'left'=>$capacity>0?max(0,$capacity-$booked):null,'expired'=>$expired,'full'=>$full];})->values();
        return view('admin.bookings.session-wise',compact('bookings','sessionTypes','sessionDates','manualCustomers','childrenByCustomer','manualSessions','sessionName','sessionDate','search'));
    }

    public function validateManual(Request $request)
    {
        $validator=Validator::make($request->all(),$this->manualRules()); if($validator->fails())return response()->json(['status'=>false,'message'=>$validator->errors()->first()],422);
        try{$result=$this->checkManualRules($request,false,false);return response()->json(['status'=>$result['status'],'message'=>$result['message'],'is_expired'=>$result['is_expired']??false],$result['status']?200:422);}catch(\Throwable $e){return response()->json(['status'=>false,'message'=>$e->getMessage()],422);}
    }

    public function store(Request $request, TrainingMailService $mail)
    {
        $request->validate($this->manualRules());
        try{$booking=DB::transaction(function()use($request){$result=$this->checkManualRules($request,true,true);if(!$result['status'])throw new \RuntimeException($result['message']);$customer=$result['customer'];$child=$result['child'];$session=$result['session'];$credit=$result['credit'];$booking=EMSessionBooking::create(['booking_no'=>$this->bookingNo(),'customer_id'=>$customer->id,'child_id'=>$child->id,'customer_child_id'=>$child->id,'session_event_id'=>$session->id,'package_id'=>$credit->package_id,'credit_id'=>$credit->id,'cart_id'=>null,'player_first'=>$child->first_name,'player_last'=>$child->last_name,'grad_year'=>is_numeric($child->class_year)?(int)$child->class_year:null,'positions'=>$child->position,'status'=>'booked','booked_at'=>now()]);$credit->remaining_classes=max(0,(int)$credit->remaining_classes-1);$credit->used_classes=(int)$credit->used_classes+1;if((int)$credit->remaining_classes<=0)$credit->status='used';$credit->save();EMCustomerCreditLog::create(['customer_id'=>$customer->id,'credit_id'=>$credit->id,'booking_id'=>$booking->id,'type'=>'used','classes'=>1,'note'=>'Admin manual booking created for '.($session->training_type?:$session->name),'description'=>'One class credit used by admin booking.']);return$booking;});$mail->bookingCreated($booking);return back()->with('success','Booking '.$booking->booking_no.' created successfully, 1 credit was deducted, and the customer was emailed.');}catch(\Throwable $e){return back()->withInput()->with('error',$e->getMessage());}
    }

    public function updateSession(Request $request, EMSessionBooking $booking, TrainingMailService $mail)
    {
        $data=$request->validate(['session_event_id'=>'required|integer|exists:em_session_events,id']);$new=EMSessionEvent::where('is_active',1)->findOrFail($data['session_event_id']);if($this->isExpired($new))return back()->with('error','Selected session is already expired.');if((int)$booking->session_event_id===(int)$new->id)return back()->with('success','Booking is already assigned to this session.');if($this->sessionFull($new,$booking->id))return back()->with('error','Selected session is full.');$childId=$booking->child_id?:$booking->customer_child_id;if(EMSessionBooking::where('id','!=',$booking->id)->where('session_event_id',$new->id)->whereIn('status',self::ACTIVE_STATUSES)->where(fn($q)=>$q->where('child_id',$childId)->orWhere('customer_child_id',$childId))->exists())return back()->with('error','This player is already booked for the selected session.');$old=$booking->sessionEvent;$booking->update(['session_event_id'=>$new->id]);$mail->bookingUpdated($booking,$old,$new);return back()->with('success','Booking session updated successfully and the customer was emailed.');
    }

    public function destroy(EMSessionBooking $booking, TrainingMailService $mail)
    {
        $booking->loadMissing(['customer','child','credit','sessionEvent']);$session=$booking->sessionEvent;$creditReturned=false;DB::transaction(function()use($booking,&$creditReturned){if($booking->credit&&!in_array($booking->status,['cancelled','refunded'],true)){$credit=EMCustomerCredit::lockForUpdate()->find($booking->credit_id);if($credit){$credit->used_classes=max(0,(int)$credit->used_classes-1);$credit->remaining_classes=(int)$credit->remaining_classes+1;$credit->status='active';$credit->save();$creditReturned=true;EMCustomerCreditLog::create(['customer_id'=>$booking->customer_id,'credit_id'=>$credit->id,'booking_id'=>$booking->id,'type'=>'refund','classes'=>1,'note'=>'Admin deleted booking and returned 1 credit.','description'=>'Booking cancellation credit return.']);}}if($booking->cart_id)EMCustomerPackageCart::whereKey($booking->cart_id)->delete();$booking->delete();});$mail->bookingCancelled($booking,$session,$creditReturned);return back()->with('success','Booking deleted successfully'.($creditReturned?' and 1 credit was returned.':'.').' Customer email sent.');
    }

    public function move(Request $request, EMSessionBooking $booking, TrainingMailService $mail){return $this->updateSession($request,$booking,$mail);}
    private function manualRules():array{return['customer_id'=>'required|integer|exists:em_customers,id','child_mode'=>'required|string|in:existing,new','child_id'=>'nullable|integer','player_first'=>'nullable|string|max:190','player_last'=>'nullable|string|max:190','grad_year'=>'nullable|string|max:20','positions'=>'nullable|array','positions.*'=>'string|max:50','session_event_id'=>'required|integer|exists:em_session_events,id','allow_expired'=>'nullable|boolean'];}
    private function checkManualRules(Request $request,bool $lock,bool $createChild):array
    {
        $customer=EMCustomer::active()->find($request->customer_id);if(!$customer)return['status'=>false,'message'=>'Customer is inactive or unavailable.'];$creditQ=EMCustomerCredit::where('customer_id',$customer->id)->available()->orderByRaw('valid_until IS NULL, valid_until ASC')->orderBy('id');if($lock)$creditQ->lockForUpdate();$credit=$creditQ->first();if(!$credit)return['status'=>false,'message'=>'This customer has no active class credits.'];$sessionQ=EMSessionEvent::where('is_active',1)->whereKey($request->session_event_id);if($lock)$sessionQ->lockForUpdate();$session=$sessionQ->first();if(!$session)return['status'=>false,'message'=>'Selected session is unavailable.'];$expired=$this->isExpired($session);if($expired&&!$request->boolean('allow_expired'))return['status'=>false,'message'=>'This session is expired. Confirm that you want to book it anyway.','is_expired'=>true];if($this->sessionFull($session))return['status'=>false,'message'=>'Selected session is full.','is_expired'=>$expired];if($request->child_mode==='existing'){$child=EMCustomerChild::where('is_active',1)->find($request->child_id);if(!$child)return['status'=>false,'message'=>'Select a player.'];if(!EMCustomerChildParent::where('customer_id',$customer->id)->where('child_id',$child->id)->exists())return['status'=>false,'message'=>'The selected player is not linked to this customer.'];}else{$first=trim((string)$request->player_first);if($first==='')return['status'=>false,'message'=>'Player first name is required.'];if(!$createChild)$child=(object)['id'=>null];else{$child=EMCustomerChild::create(['first_name'=>$first,'last_name'=>$request->player_last,'class_year'=>$request->grad_year,'position'=>collect($request->positions??[])->implode(', '),'is_active'=>1]);EMCustomerChildParent::create(['customer_id'=>$customer->id,'child_id'=>$child->id,'relationship'=>'Parent 1','is_primary'=>1,'can_book'=>1,'can_pay'=>1,'can_pickup'=>0]);}}if($child->id&&EMSessionBooking::where('session_event_id',$session->id)->whereIn('status',self::ACTIVE_STATUSES)->where(fn($q)=>$q->where('child_id',$child->id)->orWhere('customer_child_id',$child->id))->exists())return['status'=>false,'message'=>'This child is already booked for this session.'];return['status'=>true,'message'=>$expired?'Booking can be created, but this session is expired.':'Booking can be created.','customer'=>$customer,'child'=>$child,'session'=>$session,'credit'=>$credit,'is_expired'=>$expired];
    }
    private function sessionFull(EMSessionEvent $session,?int $ignoreBooking=null):bool{$q=EMSessionBooking::where('session_event_id',$session->id)->whereIn('status',self::ACTIVE_STATUSES);if($ignoreBooking)$q->where('id','!=',$ignoreBooking);return(int)$session->capacity>0&&$q->count()>=(int)$session->capacity;}
    private function isExpired(EMSessionEvent $session):bool{try{$date=$session->event_date?->format('Y-m-d')??date('Y-m-d',strtotime($session->event_date));$time=$session->end_time?:($session->start_time?:'23:59:59');return\Carbon\Carbon::parse($date.' '.$time,'America/Los_Angeles')->lt(now('America/Los_Angeles'));}catch(\Throwable $e){return true;}}
    private function timeRange($s):string{try{return\Carbon\Carbon::parse($s->start_time)->format('g:i A').' - '.\Carbon\Carbon::parse($s->end_time)->format('g:i A');}catch(\Throwable $e){return trim(($s->start_time??'').' - '.($s->end_time??''),' -');}}
    private function bookingNo():string{return'BKG-'.now()->format('Ymd').'-'.strtoupper(Str::random(6));}
}
