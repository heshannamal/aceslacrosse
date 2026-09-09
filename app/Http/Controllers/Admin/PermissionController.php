<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class PermissionController extends Controller
{
    public function index(){ $permissions=Permission::withCount('userGroups')->orderBy('name')->get();return view('admin.permissions.index',compact('permissions')); }
    public function store(Request $request){$data=$request->validate(['name'=>'required|string|max:190','slug'=>'nullable|string|max:190','description'=>'nullable|string|max:1000']);$slug=Str::slug(trim((string)((($data['slug'] ?? null) ?: $data['name']))),'_');if(Permission::where('slug',$slug)->exists())return back()->withInput()->with('error','Permission slug already exists.');Permission::create(['name'=>$data['name'],'slug'=>$slug,'description'=>$data['description']??null,'active'=>1]);return back()->with('success','Permission created successfully.');}
    public function toggle(Permission $permission){$permission->update(['active'=>!$permission->active]);return back()->with('success','Permission status updated.');}
    public function destroy(Permission $permission){$permission->userGroups()->detach();$permission->delete();return back()->with('success','Permission deleted successfully.');}
}
