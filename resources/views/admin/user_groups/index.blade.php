@extends('admin.layouts.app')
@section('title','User Groups')
@section('content')
<div class="page-heading"><div><h2>User Groups</h2><p>Bundle permissions into reusable admin roles and assign users to each group.</p></div><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGroupModal"><i class="fa-solid fa-plus me-2"></i>New Group</button></div>
<div class="admin-card"><div class="admin-card-header"><h3><i class="fa-solid fa-users-gear text-primary me-2"></i>Groups</h3><span class="text-muted small">{{ $userGroups->count() }} groups</span></div><div class="table-responsive"><table class="table admin-table"><thead><tr><th>Name</th><th>Slug</th><th>Description</th><th>Users</th><th>Permissions</th><th>Status</th><th class="text-end">Actions</th></tr></thead><tbody>
@forelse($userGroups as $group)
<tr><td data-label="Name"><strong>{{ $group->name }}</strong></td><td data-label="Slug"><code>{{ $group->slug }}</code></td><td data-label="Description">{{ $group->description ?: '—' }}</td><td data-label="Users">{{ $group->users_count }}</td><td data-label="Permissions">{{ $group->permissions_count }}</td><td data-label="Status"><span class="status-badge {{ $group->active ? 'badge-soft-success' : 'badge-soft-danger' }}">{{ $group->active ? 'Active' : 'Inactive' }}</span></td><td data-label="Actions"><div class="table-actions"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.user-groups.edit',$group) }}"><i class="fa-solid fa-pen"></i></a>@if($group->slug !== 'super_admin')<form method="POST" action="{{ route('admin.user-groups.destroy',$group) }}" class="confirm-delete">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button></form>@endif</div></td></tr>
@empty<tr><td colspan="7"><div class="empty-state"><i class="fa-solid fa-users-gear"></i><h5>No user groups</h5></div></td></tr>@endforelse
</tbody></table></div></div>

<div class="modal fade" id="createGroupModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><form method="POST" action="{{ route('admin.user-groups.store') }}">@csrf
<div class="modal-header"><h5 class="modal-title">New User Group</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><div class="mb-3"><label class="form-label">Name *</label><input class="form-control" name="name" required></div><div class="mb-3"><label class="form-label">Slug</label><input class="form-control" name="slug" placeholder="operations_team"><div class="form-text">Leave blank to generate from the name.</div></div><div><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3"></textarea></div></div>
<div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary">Create Group</button></div>
</form></div></div></div>
@endsection
