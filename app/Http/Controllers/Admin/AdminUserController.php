<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class AdminUserController extends Controller
{
    public function index(){ $users=User::with('userGroups')->orderByDesc('id')->get();$userGroups=UserGroup::active()->orderBy('name')->get();return view('admin.users.index',compact('users','userGroups')); }
    public function store(Request $request){$data=$request->validate(['name'=>'required|string|max:190','email'=>'required|email|max:190|unique:users,email','password'=>'required|string|min:6|confirmed','user_group_ids'=>'nullable|array','user_group_ids.*'=>'integer|exists:user_groups,id']);$user=User::create(['name'=>$data['name'],'email'=>$data['email'],'password'=>Hash::make($data['password']),'is_admin'=>true]);$user->userGroups()->sync($request->input('user_group_ids',[]));return back()->with('success','User created successfully.');}
    public function edit(User $user){$userGroups=UserGroup::active()->orderBy('name')->get();$selectedGroupIds=$user->userGroups()->pluck('user_groups.id')->toArray();return view('admin.users.edit',compact('user','userGroups','selectedGroupIds'));}
    public function update(Request $request,User $user){$data=$request->validate(['name'=>'required|string|max:190','email'=>'required|email|max:190|unique:users,email,'.$user->id,'password'=>'nullable|string|min:6|confirmed','user_group_ids'=>'nullable|array','user_group_ids.*'=>'integer|exists:user_groups,id']);$user->name=$data['name'];$user->email=$data['email'];$user->is_admin=true;if(!empty($data['password']))$user->password=Hash::make($data['password']);$user->save();$user->userGroups()->sync($request->input('user_group_ids',[]));return redirect()->route('admin.users.index')->with('success','User updated successfully.');}
    public function destroy(User $user){if((int)$user->id===1)return back()->with('error','Main admin user cannot be deleted.');if(auth()->id()===$user->id)return back()->with('error','You cannot delete your own account.');$user->userGroups()->detach();$user->delete();return back()->with('success','User deleted successfully.');}
}
