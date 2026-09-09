@extends('admin.layouts.app')
@section('title','Permissions')
@section('content')
<div class="page-heading">
    <div><h2>Permissions</h2><p>Create permission keys and control which permissions can be assigned to user groups.</p></div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPermissionModal"><i class="fa-solid fa-plus me-2"></i>New Permission</button>
</div>
<div class="admin-card"><div class="admin-card-header"><h3><i class="fa-solid fa-lock text-primary me-2"></i>Permission Registry</h3><span class="text-muted small">{{ $permissions->count() }} permissions</span></div>
<div class="table-responsive"><table class="table admin-table"><thead><tr><th>Name</th><th>Slug</th><th>Description</th><th>Groups</th><th>Status</th><th class="text-end">Actions</th></tr></thead><tbody>
@forelse($permissions as $permission)
<tr>
<td data-label="Name"><strong>{{ $permission->name }}</strong></td><td data-label="Slug"><code>{{ $permission->slug }}</code></td><td data-label="Description">{{ $permission->description ?: '—' }}</td><td data-label="Groups">{{ $permission->user_groups_count }}</td>
<td data-label="Status"><span class="status-badge {{ $permission->active ? 'badge-soft-success' : 'badge-soft-danger' }}">{{ $permission->active ? 'Active' : 'Inactive' }}</span></td>
<td data-label="Actions"><div class="table-actions">
<form method="POST" action="{{ route('admin.permissions.toggle',$permission) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-primary" title="Toggle status"><i class="fa-solid fa-power-off"></i></button></form>
<form method="POST" action="{{ route('admin.permissions.destroy',$permission) }}" class="confirm-delete" data-confirm-text="Delete this permission and detach it from all user groups?">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button></form>
</div></td>
</tr>
@empty<tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-lock-open"></i><h5>No permissions</h5></div></td></tr>@endforelse
</tbody></table></div></div>

<div class="modal fade" id="createPermissionModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><form method="POST" action="{{ route('admin.permissions.store') }}">@csrf
<div class="modal-header"><h5 class="modal-title">New Permission</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><div class="mb-3"><label class="form-label">Name *</label><input class="form-control" name="name" required></div><div class="mb-3"><label class="form-label">Slug</label><input class="form-control" name="slug" placeholder="manage_feature"><div class="form-text">Leave blank to generate it from the name.</div></div><div><label class="form-label">Description</label><textarea class="form-control" rows="3" name="description"></textarea></div></div>
<div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary">Create Permission</button></div>
</form></div></div></div>
@endsection
