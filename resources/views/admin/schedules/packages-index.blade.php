@extends('admin.layouts.app')
@section('title','Packages')
@section('content')
<style>
.schedule-page{--a:#611eb2;--ad:#4d168f;--soft:#f4edfc}.schedule-top{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;margin-bottom:18px}.schedule-top h2{font-weight:900;margin:0}.schedule-top p{color:#64748b;margin:6px 0 0}.schedule-card{background:#fff;border:1px solid #e2e8f0;border-radius:18px;padding:20px}.schedule-filter{display:grid;grid-template-columns:2fr 1fr 1fr auto auto;gap:12px;align-items:end;background:linear-gradient(135deg,#fff,#faf7ff);border:1px solid #e3d4f2;border-radius:18px;padding:14px;margin-bottom:20px}.s-label{font-size:11px;font-weight:900;text-transform:uppercase;color:#556070;margin-bottom:6px}.s-input{height:40px;border-radius:999px;border:1px solid #d8dee8;padding:0 14px;width:100%}.s-input:focus{outline:0;border-color:var(--a);box-shadow:0 0 0 3px rgba(97,30,178,.1)}.s-btn{height:40px;border-radius:999px;font-weight:900;padding:0 18px}.package-list{display:flex;flex-direction:column;gap:10px}.package-row{display:flex;justify-content:space-between;gap:16px;align-items:center;border:1px solid #dfe5ed;border-radius:15px;padding:16px}.package-row:hover{border-color:var(--a);box-shadow:0 10px 24px rgba(97,30,178,.08)}.package-row h4{font-size:17px;font-weight:900;margin:0}.pill{display:inline-flex;background:var(--soft);color:var(--a);border-radius:999px;padding:6px 10px;font-size:11px;font-weight:900;margin:8px 6px 0 0}.pill.user{background:#eef4ff;color:#3448a4}.pill.guest{background:#f4edfc;color:#611eb2}.round-icon{width:36px;height:36px;border:0;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;background:#f8fafc}.round-icon:hover{background:var(--soft);color:var(--a)}@media(max-width:900px){.schedule-filter{grid-template-columns:1fr 1fr}.package-row{align-items:flex-start}}@media(max-width:575px){.schedule-filter{grid-template-columns:1fr}.package-row{flex-direction:column}.s-btn{width:100%}}
</style>
<div class="schedule-page">
    <div class="schedule-top"><div><h2>Packages</h2><p>Create and manage separate ACES Training prices for admin-added Users and online Guests.</p></div><div class="d-flex align-items-center gap-2"><span class="badge rounded-pill text-bg-light text-primary px-3 py-2"><i class="fa-solid fa-box-open me-1"></i>{{ $packages->total() }} Packages</span><a href="{{ route('admin.em.packages.create') }}" class="btn btn-primary rounded-pill fw-bold px-4"><i class="fa-solid fa-plus me-2"></i>Add Package</a></div></div>
    <div class="schedule-card">
        <form method="GET" class="schedule-filter"><div><label class="s-label">Search</label><input name="search" class="s-input" value="{{ $search }}" placeholder="Search package name or description..."></div><div><label class="s-label">Classes</label><input type="number" min="1" name="classes" class="s-input" value="{{ $classFilter }}" placeholder="Any"></div><div><label class="s-label">Max Price</label><input type="number" min="0" step="0.01" name="price" class="s-input" value="{{ $priceFilter }}" placeholder="Either tier"></div><button class="btn btn-primary s-btn"><i class="fa-solid fa-filter me-1"></i>Filter</button><a href="{{ route('admin.em.packages.index') }}" class="btn btn-dark s-btn d-flex align-items-center justify-content-center">Reset</a></form>
        <div class="package-list">
            @forelse($packages as $package)
                <div class="package-row">
                    <div>
                        <h4>{{ $package->package_name }}</h4>
                        <div class="text-muted small mt-1">{{ \Illuminate\Support\Str::limit($package->package_description, 150) ?: 'No description' }}</div>
                        <span class="pill user"><i class="fa-solid fa-user-check me-1"></i>User ${{ number_format((float) $package->getRawOriginal('package_price'), 2) }}</span>
                        <span class="pill guest"><i class="fa-solid fa-user-plus me-1"></i>Guest ${{ number_format((float) ($package->guest_price ?? $package->getRawOriginal('package_price')), 2) }}</span>
                        <span class="pill">{{ $package->available_classes }} {{ (int) $package->available_classes === 1 ? 'Class' : 'Classes' }}</span>
                    </div>
                    <div class="d-flex gap-2"><a href="{{ route('admin.em.packages.edit', $package) }}" class="round-icon text-decoration-none"><i class="fa-solid fa-pen"></i></a><form method="POST" action="{{ route('admin.em.packages.destroy', $package) }}" onsubmit="return confirm('Delete this package?');">@csrf @method('DELETE')<button class="round-icon text-danger"><i class="fa-solid fa-trash"></i></button></form></div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">No packages found.</div>
            @endforelse
        </div>
        @if($packages->hasPages())<div class="mt-3">{{ $packages->links() }}</div>@endif
    </div>
</div>
@endsection
