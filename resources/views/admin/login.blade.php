<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | ACES Lacrosse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ asset('public/admin_assets/css/admin.css') }}" rel="stylesheet">
</head>
<body>
<div class="login-page">
    <section class="login-brand">
        <img src="{{ asset('public/assets/images/ACES-logo.webp') }}" alt="ACES Lacrosse">
        <h1>ACES LACROSSE</h1>
        <p>Admin Management Portal</p>
    </section>
    <section class="login-box">
        <div class="login-card">
            <div class="mb-4">
                <span class="text-primary fw-bold">ACES LACROSSE</span>
                <h2 class="mt-2">Welcome back</h2>
                <p class="text-secondary">Sign in to manage members, bookings, packages, sessions and payments.</p>
            </div>
            @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="mb-3"><label class="form-label fw-semibold">Email</label><input type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus></div>
                <div class="mb-3"><label class="form-label fw-semibold">Password</label><input type="password" class="form-control" name="password" required></div>
                <div class="form-check mb-4"><input class="form-check-input" type="checkbox" name="remember" value="1" id="remember"><label class="form-check-label" for="remember">Remember me</label></div>
                <button class="btn btn-primary w-100 login-submit">Sign In</button>
            </form>
            <p class="text-secondary small mt-4 mb-0">Authorized ACES Lacrosse staff only.</p>
        </div>
    </section>
</div>
</body>
</html>
