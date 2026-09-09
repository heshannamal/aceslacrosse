@extends('pages.customer_sessions.portal.layout')
@section('title','Training Dashboard | Alcatraz Outlaws')
@section('content')
<style>
:root{--r:#f3282c}.td-hero{position:relative;overflow:hidden;border-radius:30px;background:radial-gradient(circle at top right,rgba(243,40,44,.35),transparent 30%),linear-gradient(135deg,#050505,#151515 55%,#2b0d0f);color:#fff;padding:28px;margin-bottom:24px;box-shadow:0 22px 50px rgba(0,0,0,.14)}.td-hero:after{content:"";position:absolute;width:260px;height:260px;border-radius:50%;right:-100px;top:-120px;background:rgba(255,255,255,.06)}.td-hero>*{position:relative;z-index:1}.td-kicker{display:inline-flex;gap:7px;align-items:center;padding:8px 12px;border-radius:999px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.12);color:var(--r);font-size:11px;font-weight:900;letter-spacing:.14em}.td-title{font-family:'Archivo Black',sans-serif;font-size:clamp(38px,4vw,64px);line-height:.92;letter-spacing:-.055em;margin:14px 0 0}.td-stat{height:100%;min-height:118px;padding:16px;border-radius:20px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.14)}.td-stat span{display:block;color:rgba(255,255,255,.55);font-size:10px;font-weight:900;letter-spacing:.12em;text-transform:uppercase}.td-stat strong{display:block;margin-top:9px;font-family:'Archivo Black',sans-serif;font-size:38px}.td-stat strong.red{color:var(--r)}.td-group{border:1px solid rgba(0,0,0,.08);border-radius:22px;background:#fff;box-shadow:0 10px 28px rgba(0,0,0,.04);overflow:hidden;margin-bottom:14px}.td-group>button{width:100%;border:0;background:radial-gradient(circle at top right,rgba(243,40,44,.08),transparent 34%),#fff;padding:18px;display:flex;align-items:center;justify-content:space-between;gap:14px;text-align:left}.td-left{display:flex;align-items:center;gap:13px}.td-icon{width:44px;height:44px;border-radius:50%;background:#fff0f0;color:var(--r);display:grid;place-items:center}.td-left h3{margin:0;font-family:'Archivo Black',sans-serif;font-size:23px;letter-spacing:-.04em}.td-left p{margin:4px 0 0;color:#667085;font-size:11px;font-weight:700}.td-right{display:flex;align-items:center;gap:10px}.td-count{padding:7px 11px;border-radius:999px;background:#f3f4f6;font-size:11px;font-weight:900}.td-arrow{width:32px;height:32px;border-radius:50%;background:#050505;color:#fff;display:grid;place-items:center;transition:.18s}.td-group>button[aria-expanded=true] .td-arrow{background:var(--r);transform:rotate(180deg)}.td-body{padding:0 18px 18px}.td-empty{padding:25px;border-radius:16px;background:#fafafa;color:#667085;text-align:center;font-weight:700}.td-booking-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;padding-top:4px}.mb-card{overflow:hidden;border:1px solid rgba(0,0,0,.08);border-radius:22px;background:radial-gradient(circle at top right,rgba(243,40,44,.08),transparent 34%),#fff;box-shadow:0 12px 28px rgba(0,0,0,.05)}.mb-head{display:flex;justify-content:space-between;gap:14px;padding:17px;border-bottom:1px solid rgba(0,0,0,.07)}.mb-head h3{margin:0;font-family:'Archivo Black',sans-serif;font-size:24px;letter-spacing:-.04em}.mb-no{display:block;margin-top:5px;font-size:9px;font-weight:900;letter-spacing:.12em;color:#667085}.mb-session-chip{display:inline-flex;align-items:center;gap:5px;margin-top:10px;border-radius:999px;background:#fff0f0;color:var(--r);padding:6px 9px;font-size:9px;font-weight:900;text-transform:uppercase;letter-spacing:.08em}.mb-session-chip.is-past{background:#f3f4f6;color:#667085}.mb-date{min-width:66px;height:max-content;border-radius:18px;background:#050505;color:#fff;text-align:center;padding:8px}.mb-date strong{display:block;color:var(--r);font-size:25px;line-height:1}.mb-date span{font-size:10px;font-weight:900}.mb-body{padding:16px}.mb-details{display:grid;grid-template-columns:1fr 1fr;gap:10px}.mb-detail{padding:11px;border-radius:14px;background:#f5f5f6}.mb-detail span{display:block;color:#667085;font-size:9px;font-weight:900;letter-spacing:.12em;text-transform:uppercase}.mb-detail strong{display:block;margin-top:5px;font-size:12px}.mb-detail strong small{display:block;margin-top:4px;color:#667085;font-size:10px;font-weight:700}.mb-detail.full{grid-column:1/-1}.mb-status-inline{display:inline-flex!important;width:max-content;border-radius:999px;background:#ecfdf3;color:#166534;padding:5px 8px;font-size:9px!important;text-transform:uppercase}.mb-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-top:13px}.mb-actions a,.mb-actions button{border:0;border-radius:999px;padding:9px 13px;font-size:11px;font-weight:900;text-decoration:none}.mb-map{background:#050505;color:#fff}.mb-map:hover{color:#fff;background:var(--r)}.mb-cancel{background:#fff1f2;color:#b42318;border:1px solid #fecdd3!important}@media(max-width:1050px){.td-booking-grid{grid-template-columns:1fr}}@media(max-width:767px){.td-hero{padding:22px}.td-title{font-size:42px}.td-left h3{font-size:19px}.td-right{align-self:stretch;justify-content:space-between}.td-group>button{align-items:flex-start;flex-direction:column}.mb-details{grid-template-columns:1fr}.mb-detail.full{grid-column:auto}}
</style>

<section class="td-hero"><div class="row g-3 align-items-center"><div class="col-lg-7"><div class="td-kicker"><i class="fa-solid fa-bolt"></i>PARENT DASHBOARD</div><h1 class="td-title">Welcome Back,<br>{{ $customer->display_name }}</h1></div><div class="col-lg-5"><div class="row g-3"><div class="col-4"><div class="td-stat"><span>Available Credits</span><strong class="red">{{ $remainingCredits }}</strong></div></div><div class="col-4"><div class="td-stat"><span>Used Credits</span><strong>{{ $usedCredits }}</strong></div></div><div class="col-4"><div class="td-stat"><span>Bookings</span><strong>{{ $bookingsCount }}</strong></div></div></div></div></div></section>

@php
    $dashboardGroups = [
        ['id'=>'upcomingDash','title'=>'Upcoming Sessions','subtitle'=>'Nearest session first.','items'=>$upcomingBookings,'icon'=>'fa-regular fa-calendar-check','key'=>'upcoming'],
        ['id'=>'pastDash','title'=>'Past Sessions','subtitle'=>'Already held sessions, newest first.','items'=>$pastBookings,'icon'=>'fa-solid fa-clock-rotate-left','key'=>'past'],
    ];
@endphp

@foreach($dashboardGroups as $group)
    <div class="td-group">
        <button type="button" data-bs-toggle="collapse" data-bs-target="#{{ $group['id'] }}" aria-expanded="false" aria-controls="{{ $group['id'] }}">
            <div class="td-left"><span class="td-icon"><i class="{{ $group['icon'] }}"></i></span><div><h3>{{ $group['title'] }}</h3><p>{{ $group['subtitle'] }}</p></div></div>
            <div class="td-right"><span class="td-count">{{ $group['items']->count() }} bookings</span><span class="td-arrow"><i class="fa-solid fa-chevron-down"></i></span></div>
        </button>
        <div class="collapse" id="{{ $group['id'] }}">
            <div class="td-body">
                @if($group['items']->isEmpty())
                    <div class="td-empty">No bookings in this section.</div>
                @else
                    <div class="td-booking-grid">
                        @foreach($group['items'] as $booking)
                            @include('pages.customer_sessions.portal.booking-card', ['booking'=>$booking,'bookingKey'=>$group['key']])
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endforeach
@endsection
