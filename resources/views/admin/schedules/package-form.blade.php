@extends('admin.layouts.app')
@section('title', $package ? 'Edit Package' : 'Create Package')
@section('content')
<style>
.form-card{background:#fff;border:1px solid #e2e8f0;border-radius:18px;overflow:hidden}.form-head{padding:20px 24px;background:linear-gradient(90deg,#fff,#fff7f7);border-bottom:1px solid #edf0f4;display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap}.form-body{padding:24px}.form-title{font-size:23px;font-weight:900;margin:0}.form-label{font-weight:800}.form-control:focus{border-color:#f3282c;box-shadow:0 0 0 .2rem rgba(243,40,44,.1)}.info-box{background:#fff7f7;border:1px solid #f6cbcc;border-radius:14px;padding:14px;color:#64748b}.price-card{height:100%;padding:18px;border:1px solid #e5e7eb;border-radius:16px;background:#fbfcfe}.price-card.guest{background:#fff7f7;border-color:#f7d1d2}.price-title{font-size:12px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;color:#475569;margin-bottom:8px}.price-help{font-size:12px;color:#64748b;margin-top:7px;line-height:1.45}
</style>

<form class="form-card" method="POST" action="{{ $package ? route('admin.em.packages.update', $package) : route('admin.em.packages.store') }}">
    @csrf
    @if($package) @method('PUT') @endif

    <div class="form-head">
        <div><h2 class="form-title">{{ $package ? 'Edit Package' : 'Create Package' }}</h2><div class="text-muted small mt-1">Set separate member/user and guest pricing for Training accounts.</div></div>
        <a class="btn btn-outline-dark rounded-pill" href="{{ route('admin.em.packages.index') }}">Back</a>
    </div>

    <div class="form-body">
        @if($errors->any())
            <div class="alert alert-danger rounded-3"><strong>Please correct the form.</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <div class="row g-4">
            <div class="col-md-6"><label class="form-label">Package Name *</label><input class="form-control" name="package_name" value="{{ old('package_name', optional($package)->package_name) }}" required></div>
            <div class="col-md-6"><label class="form-label">Available Classes *</label><input type="number" min="1" class="form-control" name="available_classes" value="{{ old('available_classes', optional($package)->available_classes) }}" required></div>

            <div class="col-md-6">
                <div class="price-card">
                    <div class="price-title"><i class="fa-solid fa-user-check me-1"></i> User / Member Price *</div>
                    <div class="input-group"><span class="input-group-text">$</span><input type="number" step="0.01" min="0" class="form-control" name="package_price" value="{{ old('package_price', optional($package)->getRawOriginal('package_price')) }}" required></div>
                    <div class="price-help">Used only for Training customers created/imported from the Admin panel.</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="price-card guest">
                    <div class="price-title"><i class="fa-solid fa-user-plus me-1"></i> Guest Price *</div>
                    <div class="input-group"><span class="input-group-text">$</span><input type="number" step="0.01" min="0" class="form-control" name="guest_price" value="{{ old('guest_price', optional($package)->guest_price ?? optional($package)->getRawOriginal('package_price')) }}" required></div>
                    <div class="price-help">Used for visitors and customers who create their own Training account online.</div>
                </div>
            </div>

            <div class="col-12"><label class="form-label">Package Description</label><textarea class="form-control" rows="5" name="package_description">{{ old('package_description', optional($package)->package_description) }}</textarea></div>

            <div class="col-12"><div class="info-box"><strong class="text-dark"><i class="fa-solid fa-circle-info text-primary me-2"></i>Pricing rule</strong><div class="mt-1">Admin-added customers are Users and pay the User / Member Price. Self-registered Training customers are Guests and pay the Guest Price. The correct price is also enforced in the cart and checkout.</div></div></div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4"><a class="btn btn-outline-secondary rounded-pill px-4" href="{{ route('admin.em.packages.index') }}">Cancel</a><button class="btn btn-primary rounded-pill px-4 fw-bold">{{ $package ? 'Update Package' : 'Create Package' }}</button></div>
    </div>
</form>
@endsection
