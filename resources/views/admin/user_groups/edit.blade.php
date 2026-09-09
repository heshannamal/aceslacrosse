@extends('admin.layouts.app')
@section('title','Edit User Group')
@section('content')
<div class="page-heading"><div><h2>Edit User Group</h2><p>Configure group identity, permissions and assigned admin users.</p></div><a class="btn btn-light border" href="{{ route('admin.user-groups.index') }}"><i class="fa-solid fa-arrow-left me-2"></i>Back</a></div>
<form method="POST" action="{{ route('admin.user-groups.update',$userGroup) }}">@csrf @method('PUT')
<div class="row g-3">
<div class="col-lg-5"><div class="admin-card"><div class="admin-card-header"><h3>Group Details</h3></div><div class="admin-card-body">
<div class="mb-3"><label class="form-label">Name *</label><input class="form-control" name="name" value="{{ old('name',$userGroup->name) }}" required></div>
<div class="mb-3"><label class="form-label">Slug</label><input class="form-control" name="slug" value="{{ old('slug',$userGroup->slug) }}"></div>
<div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="4">{{ old('description',$userGroup->description) }}</textarea></div>
<div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="active" value="1" id="activeGroup" @checked($userGroup->active)><label class="form-check-label" for="activeGroup">Active group</label></div>
</div></div></div>
<div class="col-lg-7"><div class="admin-card mb-3"><div class="admin-card-header"><h3>Permissions</h3><span class="text-muted small">Select all that apply</span></div><div class="admin-card-body"><div class="row g-2">
@foreach($permissions as $permission)<div class="col-md-6"><label class="checkbox-card"><input class="form-check-input mt-1" type="checkbox" name="permission_ids[]" value="{{ $permission->id }}" @checked(in_array($permission->id,$selectedPermissionIds))><span><strong class="d-block">{{ $permission->name }}</strong><small class="text-muted">{{ $permission->slug }}</small></span></label></div>@endforeach
</div></div></div>
<div class="admin-card"><div class="admin-card-header"><h3>Assigned Users</h3></div><div class="admin-card-body"><div class="row g-2">
@foreach($users as $user)<div class="col-md-6"><label class="checkbox-card"><input class="form-check-input mt-1" type="checkbox" name="user_ids[]" value="{{ $user->id }}" @checked(in_array($user->id,$selectedUserIds))><span><strong class="d-block">{{ $user->name }}</strong><small class="text-muted">{{ $user->email }}</small></span></label></div>@endforeach
</div></div></div></div>
</div>
<div class="d-flex justify-content-end gap-2 mt-3"><a class="btn btn-light border" href="{{ route('admin.user-groups.index') }}">Cancel</a><button class="btn btn-primary px-4">Save Group</button></div>
</form>
@endsection
