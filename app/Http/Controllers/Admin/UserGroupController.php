<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class UserGroupController extends Controller
{
    public function index(){ $userGroups=UserGroup::withCount(['users','permissions'])->orderBy('name')->get();return view('admin.user_groups.index',compact('userGroups')); }
    public function store(Request $request){$data=$request->validate(['name'=>'required|string|max:190','slug'=>'nullable|string|max:190','description'=>'nullable|string|max:1000']);$slug=Str::slug(trim((string)((($data['slug'] ?? null) ?: $data['name']))),'_');if(UserGroup::where('slug',$slug)->exists())return back()->withInput()->with('error','User group slug already exists.');UserGroup::create(['name'=>$data['name'],'slug'=>$slug,'description'=>$data['description']??null,'active'=>1]);return back()->with('success','User group created successfully.');}
    public function edit(UserGroup $userGroup){$permissions=Permission::orderBy('name')->get();$users=User::orderByDesc('id')->get();$selectedPermissionIds=$userGroup->permissions()->pluck('permissions.id')->toArray();$selectedUserIds=$userGroup->users()->pluck('users.id')->toArray();return view('admin.user_groups.edit',compact('userGroup','permissions','users','selectedPermissionIds','selectedUserIds'));}
    public function update(Request $request,UserGroup $userGroup){$data=$request->validate(['name'=>'required|string|max:190','slug'=>'nullable|string|max:190','description'=>'nullable|string|max:1000','active'=>'nullable|integer|in:1','permission_ids'=>'nullable|array','permission_ids.*'=>'integer|exists:permissions,id','user_ids'=>'nullable|array','user_ids.*'=>'integer|exists:users,id']);$slug=Str::slug(trim((string)((($data['slug'] ?? null) ?: $data['name']))),'_');if(UserGroup::where('slug',$slug)->where('id','!=',$userGroup->id)->exists())return back()->withInput()->with('error','User group slug already exists.');$userGroup->update(['name'=>$data['name'],'slug'=>$slug,'description'=>$data['description']??null,'active'=>$request->has('active')]);$userGroup->permissions()->sync($request->input('permission_ids',[]));$userGroup->users()->sync($request->input('user_ids',[]));return redirect()->route('admin.user-groups.index')->with('success','User group updated successfully.');}
    public function destroy(UserGroup $userGroup){if($userGroup->slug==='super_admin')return back()->with('error','Super Admin group cannot be deleted.');$userGroup->permissions()->detach();$userGroup->users()->detach();$userGroup->delete();return back()->with('success','User group deleted successfully.');}
}
