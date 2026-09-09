@extends('layouts.app')

@section('title', 'Training | ACES Lacrosse')

@section('content')
@php
    $landingSessions = collect($sessions ?? []);
    $calendarSessions = collect($calendarSessions ?? $landingSessions);
    $landingPackages = collect($packages ?? []);
    $children = collect($children ?? []);
    $isLoggedIn = !empty($customer);
    $remainingCredits = (int) ($remainingCredits ?? data_get($summary ?? [], 'remaining_credits', 0));
    $usedCredits = (int) ($usedCredits ?? data_get($summary ?? [], 'used_credits', 0));
    $bookingsCount = (int) ($bookingsCount ?? data_get($summary ?? [], 'booked_sessions', 0));
    $trainingCartCount = (int) ($trainingCartCount ?? $cartCount ?? 0);
    $calendarGroups = $calendarSessions->groupBy(function ($session) {
        return !empty($session->event_date)
            ? \Carbon\Carbon::parse($session->event_date)->format('F Y')
            : 'Upcoming';
    });
@endphp

<div class="aces-training-page">
    @if(session('success'))
        <div class="aces-training-width pt-3"><div class="aces-training-alert success"><i class="fa-solid fa-circle-check"></i>{{ session('success') }}</div></div>
    @endif
    @if(session('error'))
        <div class="aces-training-width pt-3"><div class="aces-training-alert error"><i class="fa-solid fa-circle-exclamation"></i>{{ session('error') }}</div></div>
    @endif
    @if($errors->any())
        <div class="aces-training-width pt-3"><div class="aces-training-alert error"><i class="fa-solid fa-circle-exclamation"></i>{{ $errors->first() }}</div></div>
    @endif

    <section class="aces-training-hero">
        <div class="aces-training-hero-bg"></div>
        <div class="aces-training-hero-overlay"></div>
        <div class="aces-training-width aces-training-hero-inner">
            <div class="aces-training-hero-copy">
                <span class="aces-training-pill">ACES LACROSSE TRAINING</span>
                <h1>Train with purpose.<br><span>Play with confidence.</span></h1>
                <p>Build stick skills, speed, decision-making and game-ready habits with focused ACES Lacrosse training sessions.</p>
                <div class="aces-training-hero-actions">
                    <a class="aces-training-btn primary" href="#trainingSessions">View Sessions <i class="fa-regular fa-calendar"></i></a>
                    @if($isLoggedIn)
                        <a class="aces-training-btn glass" href="{{ route('em.customer.dashboard') }}">My Dashboard <i class="fa-solid fa-arrow-right"></i></a>
                    @else
                        <a class="aces-training-btn glass" href="{{ route('em.customer.login', ['redirect' => route('em.customer.index')]) }}">Training Login <i class="fa-solid fa-right-to-bracket"></i></a>
                    @endif
                </div>
            </div>

            <div class="aces-training-hero-card">
                <img src="{{ asset('public/assets/images/ACES-logo.webp') }}" alt="ACES Lacrosse">
                @if($isLoggedIn)
                    <small>WELCOME BACK</small>
                    <strong>{{ $customer->display_name }}</strong>
                    <div class="aces-training-stats">
                        <div><b>{{ $remainingCredits }}</b><span>Credits</span></div>
                        <div><b>{{ $bookingsCount }}</b><span>Bookings</span></div>
                        <div><b>{{ $trainingCartCount }}</b><span>Cart</span></div>
                    </div>
                @else
                    <small>READY TO TRAIN?</small>
                    <strong>Book your next session</strong>
                    <p>Create a parent account, add your player and manage all Training bookings in one place.</p>
                    <a href="{{ route('em.customer.register') }}">Create Training Account <i class="fa-solid fa-user-plus"></i></a>
                @endif
            </div>
        </div>
    </section>

    <section class="aces-training-section" id="trainingSessions">
        <div class="aces-training-width">
            <div class="aces-training-heading">
                <div>
                    <span>UPCOMING ACES TRAINING</span>
                    <h2>Training Sessions</h2>
                    <p>Select a session, review the details and book the right player.</p>
                </div>
                <a href="#trainingCalendar" class="aces-training-text-link">View calendar <i class="fa-solid fa-arrow-down"></i></a>
            </div>

            @if($landingSessions->isEmpty())
                <div class="aces-training-empty">
                    <img src="{{ asset('public/assets/images/ACES-logo.webp') }}" alt="ACES Lacrosse">
                    <h3>No upcoming sessions yet</h3>
                    <p>Check back soon for new ACES Lacrosse Training dates.</p>
                </div>
            @else
                <div class="aces-session-grid">
                    @foreach($landingSessions as $session)
                        @php
                            $sessionTitle = $session->training_type ?: ($session->name ?: 'Training');
                            $sessionDate = !empty($session->event_date) ? \Carbon\Carbon::parse($session->event_date) : null;
                            $sessionStart = !empty($session->start_time) ? \Carbon\Carbon::parse($session->start_time)->format('g:i A') : '-';
                            $sessionEnd = !empty($session->end_time) ? \Carbon\Carbon::parse($session->end_time)->format('g:i A') : '-';
                            $location = collect([
                                $session->location ?? null,
                                $session->street_address ?? null,
                                $session->city ?? null,
                            ])->map(fn ($value) => trim((string) $value))->filter()->unique()->implode(', ');
                            $instructors = collect(explode(',', (string) ($session->instructor ?? '')))
                                ->map(fn ($value) => trim($value))->filter()->values();
                            $isFull = (bool) ($session->is_full ?? false);
                            $spotsLeft = isset($session->spots_left) ? max(0, (int) $session->spots_left) : null;
                        @endphp

                        <article class="aces-session-card">
                            <div class="aces-session-card-head">
                                <div>
                                    <span class="aces-session-type">{{ strtoupper($sessionTitle) }}</span>
                                    <h3>{{ $sessionTitle }}</h3>
                                </div>
                                @if($sessionDate)
                                    <div class="aces-session-date">
                                        <b>{{ $sessionDate->format('d') }}</b>
                                        <span>{{ strtoupper($sessionDate->format('M')) }}<small>{{ strtoupper($sessionDate->format('D')) }}</small></span>
                                    </div>
                                @endif
                            </div>

                            <div class="aces-session-details">
                                <div><i class="fa-regular fa-clock"></i><span><small>TIME</small><b>{{ $sessionStart }}{{ $sessionEnd !== '-' ? ' - '.$sessionEnd : '' }}</b></span></div>
                                <div><i class="fa-solid fa-location-dot"></i><span><small>LOCATION</small><b>{{ $location ?: 'Location coming soon' }}</b></span></div>
                                <div><i class="fa-regular fa-user"></i><span><small>INSTRUCTOR</small><b>{{ $instructors->isNotEmpty() ? $instructors->implode(', ') : 'TBA' }}</b></span></div>
                            </div>

                            <div class="aces-session-card-foot">
                                @if($isFull)
                                    <span class="aces-spots full">Session Full</span>
                                @elseif($spotsLeft !== null)
                                    <span class="aces-spots"><i class="fa-solid fa-circle"></i>{{ $spotsLeft }} spot{{ $spotsLeft === 1 ? '' : 's' }} left</span>
                                @else
                                    <span class="aces-spots"><i class="fa-solid fa-circle"></i>Available</span>
                                @endif
                                <div class="aces-session-actions">
                                    <button type="button" class="aces-mini-btn outline" data-bs-toggle="modal" data-bs-target="#trainingDetails{{ $session->id }}">Details</button>
                                    @if($isFull)
                                        <button type="button" class="aces-mini-btn primary" disabled>Full</button>
                                    @elseif(!$isLoggedIn)
                                        <a class="aces-mini-btn primary" href="{{ route('em.customer.login', ['redirect' => route('em.customer.index')]) }}">Book</a>
                                    @else
                                        <button type="button" class="aces-mini-btn primary" data-bs-toggle="modal" data-bs-target="#trainingBook{{ $session->id }}">Book</button>
                                    @endif
                                </div>
                            </div>
                        </article>

                        <div class="modal fade" id="trainingDetails{{ $session->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content aces-training-modal">
                                    <div class="aces-training-modal-head">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ asset('public/assets/images/ACES-logo.webp') }}" alt="ACES Lacrosse">
                                            <div><small>ACES TRAINING SESSION</small><h3>{{ $sessionTitle }}</h3></div>
                                        </div>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="aces-training-modal-body">
                                        <p class="aces-training-modal-description">{{ $session->description ?: 'Focused ACES Lacrosse Training session designed to build confident, game-ready players.' }}</p>
                                        <div class="aces-modal-detail-grid">
                                            <div><span>Date</span><strong>{{ $sessionDate ? $sessionDate->format('l, F j, Y') : '-' }}</strong></div>
                                            <div><span>Time</span><strong>{{ $sessionStart }}{{ $sessionEnd !== '-' ? ' - '.$sessionEnd : '' }}</strong></div>
                                            <div><span>Location</span><strong>{{ $location ?: '-' }}</strong></div>
                                            <div><span>Instructor(s)</span><strong>{{ $instructors->isNotEmpty() ? $instructors->implode(', ') : 'TBA' }}</strong></div>
                                        </div>
                                        @if(!empty($session->what_to_bring))
                                            <div class="aces-what-bring"><span>WHAT TO BRING</span><p>{{ $session->what_to_bring }}</p></div>
                                        @endif
                                        @if(!$isFull)
                                            <div class="d-flex justify-content-end mt-4">
                                                @if($isLoggedIn)
                                                    <button class="aces-training-btn primary" type="button" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#trainingBook{{ $session->id }}">Book This Session</button>
                                                @else
                                                    <a class="aces-training-btn primary" href="{{ route('em.customer.login', ['redirect' => route('em.customer.index')]) }}">Login to Book</a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($isLoggedIn && !$isFull)
                            <div class="modal fade" id="trainingBook{{ $session->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content aces-training-modal">
                                        <form method="POST" action="{{ route('em.customer.session.book', $session->id) }}" class="aces-booking-form">
                                            @csrf
                                            <div class="aces-training-modal-head">
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="{{ asset('public/assets/images/ACES-logo.webp') }}" alt="ACES Lacrosse">
                                                    <div><small>BOOK ACES TRAINING</small><h3>{{ $sessionTitle }}</h3></div>
                                                </div>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="aces-training-modal-body">
                                                <div class="aces-booking-summary">
                                                    <span><i class="fa-regular fa-calendar"></i>{{ $sessionDate ? $sessionDate->format('D, M j, Y') : '-' }}</span>
                                                    <span><i class="fa-regular fa-clock"></i>{{ $sessionStart }}{{ $sessionEnd !== '-' ? ' - '.$sessionEnd : '' }}</span>
                                                    <span><i class="fa-solid fa-location-dot"></i>{{ $location ?: 'TBA' }}</span>
                                                </div>

                                                <div class="aces-book-section">
                                                    <div class="aces-book-section-title"><span>1</span><div><h4>Choose a player</h4><p>Select an existing player or add a new one.</p></div></div>
                                                    @if($children->isNotEmpty())
                                                        <div class="aces-player-grid">
                                                            @foreach($children as $child)
                                                                <label class="aces-player-choice">
                                                                    <input type="radio" name="child_id" value="{{ $child->id }}" {{ $loop->first ? 'checked' : '' }}>
                                                                    <span class="aces-player-avatar">{{ strtoupper(substr($child->first_name,0,1)) }}</span>
                                                                    <span><strong>{{ trim($child->first_name.' '.$child->last_name) }}</strong><small>{{ collect([$child->team,$child->grade,$child->position])->filter()->implode(' · ') ?: 'Player' }}</small></span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    <label class="aces-player-choice add-new">
                                                        <input type="radio" name="child_id" value="" data-new-player-radio {{ $children->isEmpty() ? 'checked' : '' }}>
                                                        <span class="aces-player-avatar"><i class="fa-solid fa-plus"></i></span>
                                                        <span><strong>Add a new player</strong><small>Create a player while booking</small></span>
                                                    </label>
                                                    <div class="aces-new-player-fields {{ $children->isEmpty() ? 'show' : '' }}" data-new-player-fields>
                                                        <div class="aces-form-grid">
                                                            <label>First Name <span>*</span><input name="player_first" placeholder="First name"></label>
                                                            <label>Last Name<input name="player_last" placeholder="Last name"></label>
                                                            <label>Grad Year<input name="grad_year" type="number" min="2000" max="2100" placeholder="2032"></label>
                                                            <label>Position<input name="positions[]" placeholder="Attack, Middie, Defense, Goalie"></label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="aces-book-section">
                                                    <div class="aces-book-section-title"><span>2</span><div><h4>Payment option</h4><p>Use a Training credit or choose a package.</p></div></div>
                                                    <div class="aces-payment-options">
                                                        @if($remainingCredits > 0)
                                                            <label class="aces-payment-choice">
                                                                <input type="radio" name="booking_payment_method" value="credit" checked>
                                                                <i class="fa-solid fa-ticket"></i>
                                                                <span><strong>Use 1 Training Credit</strong><small>{{ $remainingCredits }} available credit{{ $remainingCredits === 1 ? '' : 's' }}</small></span>
                                                            </label>
                                                        @endif
                                                        <label class="aces-payment-choice">
                                                            <input type="radio" name="booking_payment_method" value="package" {{ $remainingCredits < 1 ? 'checked' : '' }}>
                                                            <i class="fa-regular fa-credit-card"></i>
                                                            <span><strong>Buy a Training Package</strong><small>Complete checkout to confirm the booking.</small></span>
                                                        </label>
                                                    </div>
                                                    <div class="aces-package-select {{ $remainingCredits < 1 ? 'show' : '' }}" data-package-select>
                                                        <label>Training Package
                                                            <select name="selected_package_id">
                                                                <option value="">Select a package</option>
                                                                @foreach($landingPackages as $package)
                                                                    <option value="{{ $package->id }}">{{ $package->package_name }} — ${{ number_format($package->priceForCustomer($customer),2) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="aces-modal-footer">
                                                <button type="button" class="aces-training-btn outline" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="aces-training-btn primary">Confirm Booking <i class="fa-solid fa-check"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="aces-package-section" id="trainingPackages">
        <div class="aces-training-width">
            <div class="aces-training-heading light">
                <div><span>TRAIN MORE. SAVE MORE.</span><h2>Training Packages</h2><p>Choose the package that fits your player's training schedule.</p></div>
                <a class="aces-training-text-link" href="{{ route('em.customer.packages') }}">All Packages <i class="fa-solid fa-arrow-right"></i></a>
            </div>

            <div class="aces-package-grid">
                @forelse($landingPackages->take(3) as $package)
                    @php
                        $price = $package->priceForCustomer($customer ?? null);
                        $priceLabel = (!$customer || $customer->isGuestAccount()) ? 'Guest Price' : 'Member Price';
                    @endphp
                    <article class="aces-package-card">
                        <div class="aces-package-icon"><i class="fa-solid fa-ticket"></i></div>
                        <span>{{ $package->available_classes }} {{ \Illuminate\Support\Str::plural('CLASS',$package->available_classes) }}</span>
                        <h3>{{ $package->package_name }}</h3>
                        <p>{{ $package->package_description ?: 'Flexible ACES Lacrosse Training credits for upcoming sessions.' }}</p>
                        <small>{{ strtoupper($priceLabel) }}</small>
                        <strong>${{ number_format($price,2) }}</strong>
                        @if($package->available_classes > 0)<em>${{ number_format($price/$package->available_classes,2) }} per class</em>@endif
                        <div class="aces-package-actions">
                            <a href="{{ route('em.customer.package.details',$package->id) }}">Details</a>
                            <form method="POST" action="{{ route('em.customer.cart.add',$package->id) }}">@csrf<button type="submit">Add to Cart <i class="fa-solid fa-cart-plus"></i></button></form>
                        </div>
                    </article>
                @empty
                    <div class="aces-training-empty dark-empty"><h3>No packages available yet</h3><p>Please check back soon.</p></div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="aces-calendar-section" id="trainingCalendar">
        <div class="aces-training-width">
            <div class="aces-training-heading">
                <div><span>PLAN AHEAD</span><h2>Training Calendar</h2><p>All currently scheduled upcoming ACES Training sessions.</p></div>
            </div>

            @forelse($calendarGroups as $month => $monthSessions)
                <div class="aces-calendar-month">
                    <h3>{{ $month }}</h3>
                    <div class="aces-calendar-list">
                        @foreach($monthSessions as $session)
                            @php
                                $date = !empty($session->event_date) ? \Carbon\Carbon::parse($session->event_date) : null;
                                $start = !empty($session->start_time) ? \Carbon\Carbon::parse($session->start_time)->format('g:i A') : '-';
                                $title = $session->training_type ?: ($session->name ?: 'Training');
                                $location = $session->location ?: trim(($session->street_address ?? '').' '.($session->city ?? ''));
                                $full = (bool) ($session->is_full ?? false);
                            @endphp
                            <div class="aces-calendar-row">
                                <div class="aces-calendar-date"><b>{{ $date ? $date->format('d') : '--' }}</b><span>{{ $date ? strtoupper($date->format('D')) : '' }}</span></div>
                                <div class="aces-calendar-main"><strong>{{ $title }}</strong><span><i class="fa-regular fa-clock"></i>{{ $start }} @if($location)<i class="fa-solid fa-location-dot ms-2"></i>{{ $location }}@endif</span></div>
                                <span class="aces-calendar-status {{ $full ? 'full' : '' }}">{{ $full ? 'Full' : 'Available' }}</span>
                                @if(!$full)
                                    @if($isLoggedIn)
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#trainingBook{{ $session->id }}">Book</button>
                                    @else
                                        <a href="{{ route('em.customer.login', ['redirect' => route('em.customer.index')]) }}">Book</a>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="aces-training-empty"><h3>No calendar dates available yet</h3><p>New sessions will appear here as they are scheduled.</p></div>
            @endforelse
        </div>
    </section>
</div>

<style>
.aces-training-page{--purple:#611eb2;--purple-dark:#4d168f;--purple-soft:#f4edfc;--dark:#171021;--ink:#111827;--muted:#667085;--line:#e7e2ec;background:#fff;color:var(--ink);font-family:'Roboto',Arial,sans-serif}.aces-training-page *{box-sizing:border-box}.aces-training-width{width:min(1180px,calc(100% - 32px));margin:0 auto}.aces-training-alert{display:flex;align-items:center;gap:9px;padding:13px 16px;border-radius:13px;font-weight:800}.aces-training-alert.success{background:#ecfdf3;color:#166534;border:1px solid #bbf7d0}.aces-training-alert.error{background:#fff1f2;color:#991b1b;border:1px solid #fecdd3}.aces-training-hero{position:relative;min-height:620px;overflow:hidden;background:var(--dark)}.aces-training-hero-bg{position:absolute;inset:0;background:url('{{ asset('public/assets/images/hero-bg.jpg') }}') center/cover no-repeat;transform:scale(1.02)}.aces-training-hero-overlay{position:absolute;inset:0;background:linear-gradient(90deg,rgba(18,10,27,.96) 0%,rgba(24,12,36,.84) 46%,rgba(24,12,36,.34) 72%,rgba(24,12,36,.55) 100%)}.aces-training-hero-inner{position:relative;z-index:2;min-height:620px;padding:72px 0;display:grid;grid-template-columns:minmax(0,1fr) 340px;gap:55px;align-items:center}.aces-training-hero-copy{max-width:720px}.aces-training-pill{display:inline-flex;padding:9px 15px;border:1px solid rgba(208,165,255,.72);border-radius:999px;background:rgba(97,30,178,.16);color:#e4caff;font-size:11px;font-weight:900;letter-spacing:.18em}.aces-training-hero h1{margin:22px 0 18px;color:#fff;font-size:clamp(46px,5.6vw,78px);line-height:.93;letter-spacing:-.055em;font-weight:900}.aces-training-hero h1 span{color:#c897ff}.aces-training-hero-copy>p{max-width:640px;margin:0;color:rgba(255,255,255,.78);font-size:17px;font-weight:600;line-height:1.65}.aces-training-hero-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:28px}.aces-training-btn{min-height:47px;padding:0 20px;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;gap:9px;border:1px solid transparent;text-decoration:none;font-size:13px;font-weight:900;transition:.18s}.aces-training-btn.primary{background:var(--purple);color:#fff;border-color:var(--purple);box-shadow:0 12px 28px rgba(97,30,178,.3)}.aces-training-btn.primary:hover{background:var(--purple-dark);border-color:var(--purple-dark);color:#fff;transform:translateY(-1px)}.aces-training-btn.glass{background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.42);color:#fff}.aces-training-btn.glass:hover{background:#fff;color:var(--dark)}.aces-training-btn.outline{background:#fff;border-color:#cfc7d8;color:var(--dark)}.aces-training-hero-card{padding:28px;border:1px solid rgba(255,255,255,.14);border-radius:27px;background:rgba(18,10,27,.78);backdrop-filter:blur(16px);box-shadow:0 24px 60px rgba(0,0,0,.28);color:#fff}.aces-training-hero-card img{width:180px;max-height:86px;object-fit:contain;margin-bottom:24px}.aces-training-hero-card>small{display:block;color:#c897ff;font-size:10px;font-weight:900;letter-spacing:.16em}.aces-training-hero-card>strong{display:block;margin-top:8px;font-size:24px;font-weight:900}.aces-training-hero-card>p{margin:10px 0 18px;color:rgba(255,255,255,.7);font-size:13px;line-height:1.55}.aces-training-hero-card>a{color:#fff;text-decoration:none;font-size:12px;font-weight:900}.aces-training-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:20px}.aces-training-stats div{padding:13px 7px;border-radius:15px;background:rgba(255,255,255,.08);text-align:center}.aces-training-stats b{display:block;color:#d7b2ff;font-size:25px}.aces-training-stats span{display:block;margin-top:3px;color:rgba(255,255,255,.62);font-size:9px;font-weight:800;text-transform:uppercase}.aces-training-section,.aces-calendar-section{padding:68px 0 78px}.aces-training-section{background:#fff}.aces-calendar-section{background:#f7f6f9}.aces-training-heading{display:flex;align-items:flex-end;justify-content:space-between;gap:24px;margin-bottom:32px}.aces-training-heading>div>span{display:block;color:var(--purple);font-size:11px;font-weight:900;letter-spacing:.17em}.aces-training-heading h2{margin:6px 0 7px;color:var(--dark);font-size:clamp(34px,4vw,50px);font-weight:900;letter-spacing:-.045em}.aces-training-heading p{margin:0;color:var(--muted);font-size:14px;font-weight:600}.aces-training-text-link{display:inline-flex;align-items:center;gap:8px;color:var(--purple);text-decoration:none;font-size:12px;font-weight:900}.aces-session-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px}.aces-session-card{display:flex;flex-direction:column;padding:19px;border:1px solid var(--line);border-radius:23px;background:#fff;box-shadow:0 13px 32px rgba(48,27,65,.055);transition:.18s}.aces-session-card:hover{transform:translateY(-3px);box-shadow:0 18px 42px rgba(48,27,65,.09);border-color:#d9c7ed}.aces-session-card-head{min-height:76px;display:flex;align-items:flex-start;justify-content:space-between;gap:14px;padding-bottom:14px;border-bottom:1px solid #eeeaf2}.aces-session-type{display:block;color:var(--purple);font-size:9px;font-weight:900;letter-spacing:.14em}.aces-session-card-head h3{margin:6px 0 0;color:var(--dark);font-size:22px;font-weight:900;line-height:1.05}.aces-session-date{flex:0 0 auto;min-width:94px;padding:9px 11px;border-radius:16px;background:var(--dark);color:#fff;display:flex;align-items:center;justify-content:center;gap:9px}.aces-session-date b{font-size:27px;line-height:1;color:#d8b6ff}.aces-session-date span{font-size:9px;font-weight:900;line-height:1.1}.aces-session-date small{display:block;margin-top:3px;color:rgba(255,255,255,.55);font-size:8px}.aces-session-details{display:grid;gap:8px;padding:14px 0}.aces-session-details>div{display:flex;gap:10px;align-items:flex-start;padding:10px;border-radius:13px;background:#f8f7fa}.aces-session-details i{width:18px;color:var(--purple);margin-top:3px;text-align:center}.aces-session-details span{min-width:0}.aces-session-details small{display:block;color:#8a8293;font-size:8px;font-weight:900;letter-spacing:.12em}.aces-session-details b{display:block;margin-top:3px;color:#312b38;font-size:11px;line-height:1.35}.aces-session-card-foot{margin-top:auto;padding-top:13px;border-top:1px solid #eeeaf2;display:flex;align-items:center;justify-content:space-between;gap:10px}.aces-spots{display:inline-flex;align-items:center;gap:6px;color:#067647;font-size:10px;font-weight:900}.aces-spots i{font-size:6px}.aces-spots.full{color:#b42318}.aces-session-actions{display:flex;gap:7px}.aces-mini-btn{min-height:35px;padding:0 13px;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:10px;font-weight:900;border:1px solid}.aces-mini-btn.outline{background:#fff;color:var(--dark);border-color:#cbc4d1}.aces-mini-btn.primary{background:var(--purple);border-color:var(--purple);color:#fff}.aces-mini-btn.primary:disabled{opacity:.5}.aces-training-empty{grid-column:1/-1;padding:50px 24px;border:1px dashed #cec5d6;border-radius:23px;background:#fff;text-align:center}.aces-training-empty img{width:160px;max-height:80px;object-fit:contain;margin-bottom:14px}.aces-training-empty h3{margin:0;font-size:22px;font-weight:900}.aces-training-empty p{margin:7px 0 0;color:var(--muted)}.aces-training-modal{border:0!important;border-radius:24px!important;overflow:hidden;box-shadow:0 28px 75px rgba(23,16,33,.26)}.aces-training-modal-head{padding:18px 22px;background:linear-gradient(135deg,var(--dark),#3a1d54);color:#fff;display:flex;align-items:center;justify-content:space-between;gap:15px}.aces-training-modal-head img{width:115px;max-height:54px;object-fit:contain}.aces-training-modal-head small{display:block;color:#c897ff;font-size:8px;font-weight:900;letter-spacing:.15em}.aces-training-modal-head h3{margin:3px 0 0;font-size:20px;font-weight:900}.aces-training-modal-body{padding:23px}.aces-training-modal-description{color:#667085;font-size:14px;font-weight:600;line-height:1.6}.aces-modal-detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:11px}.aces-modal-detail-grid>div{padding:13px;border:1px solid #ebe7ef;border-radius:14px;background:#faf9fc}.aces-modal-detail-grid span{display:block;color:#8a8293;font-size:8px;font-weight:900;letter-spacing:.12em;text-transform:uppercase}.aces-modal-detail-grid strong{display:block;margin-top:5px;font-size:12px}.aces-what-bring{margin-top:12px;padding:14px;border-left:3px solid var(--purple);border-radius:0 14px 14px 0;background:var(--purple-soft)}.aces-what-bring span{font-size:9px;font-weight:900;color:var(--purple);letter-spacing:.12em}.aces-what-bring p{margin:5px 0 0;font-size:12px;font-weight:700}.aces-booking-summary{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px}.aces-booking-summary span{display:inline-flex;align-items:center;gap:7px;padding:8px 10px;border-radius:999px;background:#f6f3f9;color:#51485a;font-size:10px;font-weight:800}.aces-booking-summary i{color:var(--purple)}.aces-book-section{padding:19px 0;border-top:1px solid #eeeaf2}.aces-book-section-title{display:flex;gap:12px;align-items:flex-start;margin-bottom:14px}.aces-book-section-title>span{width:28px;height:28px;border-radius:50%;display:grid;place-items:center;background:var(--purple);color:#fff;font-size:11px;font-weight:900}.aces-book-section-title h4{margin:0;font-size:17px;font-weight:900}.aces-book-section-title p{margin:3px 0 0;color:#667085;font-size:11px}.aces-player-grid{display:grid;grid-template-columns:1fr 1fr;gap:9px}.aces-player-choice{position:relative;display:flex;align-items:center;gap:10px;margin:0 0 9px;padding:11px;border:1px solid #ded8e4;border-radius:14px;background:#fff;cursor:pointer}.aces-player-choice:has(input:checked){border-color:var(--purple);background:var(--purple-soft)}.aces-player-choice input{position:absolute;opacity:0;pointer-events:none}.aces-player-avatar{width:35px;height:35px;border-radius:12px;display:grid;place-items:center;background:var(--dark);color:#fff;font-size:13px;font-weight:900}.aces-player-choice:has(input:checked) .aces-player-avatar{background:var(--purple)}.aces-player-choice strong{display:block;font-size:12px}.aces-player-choice small{display:block;margin-top:2px;color:#667085;font-size:9px}.aces-player-choice.add-new{margin-top:4px}.aces-new-player-fields,.aces-package-select{display:none;padding:14px;border-radius:14px;background:#f8f7fa}.aces-new-player-fields.show,.aces-package-select.show{display:block}.aces-form-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}.aces-form-grid label,.aces-package-select label{display:block;color:#51485a;font-size:10px;font-weight:900}.aces-form-grid label span{color:var(--purple)}.aces-form-grid input,.aces-package-select select{width:100%;height:41px;margin-top:5px;padding:0 10px;border:1px solid #d8d1df;border-radius:10px;background:#fff;outline:none;font-size:12px}.aces-form-grid input:focus,.aces-package-select select:focus{border-color:var(--purple);box-shadow:0 0 0 3px rgba(97,30,178,.08)}.aces-payment-options{display:grid;grid-template-columns:1fr 1fr;gap:9px}.aces-payment-choice{position:relative;display:flex;align-items:center;gap:11px;padding:13px;border:1px solid #ded8e4;border-radius:14px;cursor:pointer}.aces-payment-choice:has(input:checked){border-color:var(--purple);background:var(--purple-soft)}.aces-payment-choice input{position:absolute;opacity:0}.aces-payment-choice>i{width:34px;height:34px;border-radius:12px;display:grid;place-items:center;background:#f2eef5;color:var(--purple)}.aces-payment-choice strong{display:block;font-size:12px}.aces-payment-choice small{display:block;margin-top:2px;color:#667085;font-size:9px}.aces-package-select{margin-top:10px}.aces-modal-footer{padding:15px 22px;border-top:1px solid #eeeaf2;background:#faf9fc;display:flex;justify-content:flex-end;gap:9px}.aces-package-section{padding:68px 0 76px;background:linear-gradient(135deg,#171021,#2b1838);color:#fff}.aces-training-heading.light h2{color:#fff}.aces-training-heading.light p{color:rgba(255,255,255,.6)}.aces-package-section .aces-training-text-link{color:#d5b2ff}.aces-package-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px}.aces-package-card{padding:23px;border:1px solid rgba(255,255,255,.12);border-radius:23px;background:rgba(255,255,255,.06);box-shadow:0 16px 35px rgba(0,0,0,.12)}.aces-package-icon{width:43px;height:43px;border-radius:14px;background:var(--purple);display:grid;place-items:center;margin-bottom:16px}.aces-package-card>span{display:block;color:#c897ff;font-size:9px;font-weight:900;letter-spacing:.14em}.aces-package-card h3{margin:7px 0 8px;font-size:23px;font-weight:900}.aces-package-card p{min-height:42px;color:rgba(255,255,255,.62);font-size:12px;line-height:1.55}.aces-package-card>small{display:block;margin-top:17px;color:rgba(255,255,255,.45);font-size:8px;font-weight:900;letter-spacing:.12em}.aces-package-card>strong{display:block;margin-top:3px;color:#fff;font-size:38px}.aces-package-card>em{display:block;color:#c8becf;font-size:10px;font-style:normal}.aces-package-actions{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:18px}.aces-package-actions a,.aces-package-actions button{width:100%;min-height:39px;border-radius:999px;display:flex;align-items:center;justify-content:center;gap:7px;font-size:10px;font-weight:900;text-decoration:none}.aces-package-actions a{border:1px solid rgba(255,255,255,.35);color:#fff}.aces-package-actions button{border:0;background:var(--purple);color:#fff}.dark-empty{background:rgba(255,255,255,.06);border-color:rgba(255,255,255,.18)}.dark-empty p{color:rgba(255,255,255,.6)}.aces-calendar-month{margin-bottom:28px}.aces-calendar-month h3{margin:0 0 10px;color:var(--dark);font-size:20px;font-weight:900}.aces-calendar-list{border:1px solid #e2dde7;border-radius:19px;overflow:hidden;background:#fff}.aces-calendar-row{display:grid;grid-template-columns:68px minmax(0,1fr) auto auto;gap:14px;align-items:center;padding:12px 15px;border-bottom:1px solid #eeeaf2}.aces-calendar-row:last-child{border-bottom:0}.aces-calendar-date{height:48px;border-radius:14px;background:var(--dark);color:#fff;display:flex;align-items:center;justify-content:center;gap:6px}.aces-calendar-date b{font-size:22px;color:#d8b6ff}.aces-calendar-date span{font-size:8px;font-weight:900}.aces-calendar-main strong{display:block;font-size:13px}.aces-calendar-main span{display:block;margin-top:4px;color:#667085;font-size:10px;font-weight:700}.aces-calendar-main i{color:var(--purple);margin-right:4px}.aces-calendar-status{padding:6px 9px;border-radius:999px;background:#ecfdf3;color:#067647;font-size:9px;font-weight:900;text-transform:uppercase}.aces-calendar-status.full{background:#fff1f2;color:#b42318}.aces-calendar-row>button,.aces-calendar-row>a{min-height:34px;padding:0 13px;border:0;border-radius:999px;background:var(--purple);color:#fff;text-decoration:none;font-size:10px;font-weight:900;display:inline-flex;align-items:center;justify-content:center}@media(max-width:1050px){.aces-training-hero-inner{grid-template-columns:1fr 300px;gap:30px}.aces-session-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.aces-package-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.aces-package-card:last-child:nth-child(odd){grid-column:1/-1}}@media(max-width:760px){.aces-training-hero,.aces-training-hero-inner{min-height:auto}.aces-training-hero-inner{grid-template-columns:1fr;padding:56px 0}.aces-training-hero-card{display:none}.aces-training-heading{align-items:flex-start;flex-direction:column}.aces-session-grid,.aces-package-grid{grid-template-columns:1fr}.aces-package-card:last-child:nth-child(odd){grid-column:auto}.aces-modal-detail-grid,.aces-player-grid,.aces-form-grid,.aces-payment-options{grid-template-columns:1fr}.aces-calendar-row{grid-template-columns:58px 1fr auto}.aces-calendar-status{display:none}.aces-calendar-row>button,.aces-calendar-row>a{grid-column:2/-1;width:max-content}.aces-session-card-foot{align-items:flex-start;flex-direction:column}.aces-session-actions{width:100%}.aces-mini-btn{flex:1}.aces-training-heading h2{font-size:38px}}@media(max-width:480px){.aces-training-width{width:min(100% - 20px,1180px)}.aces-training-hero h1{font-size:45px}.aces-training-hero-copy>p{font-size:15px}.aces-training-hero-actions{flex-direction:column}.aces-training-btn{width:100%}.aces-session-card{padding:15px}.aces-session-card-head{flex-direction:column}.aces-session-date{width:100%;justify-content:flex-start}.aces-calendar-row{padding:10px;gap:10px}.aces-calendar-main span{line-height:1.5}.aces-modal-footer{flex-direction:column}.aces-modal-footer .aces-training-btn{width:100%}}
</style>

<script>
document.addEventListener('DOMContentLoaded',function(){
    document.querySelectorAll('.aces-booking-form').forEach(function(form){
        const newPlayerFields=form.querySelector('[data-new-player-fields]');
        const packageSelect=form.querySelector('[data-package-select]');

        function syncPlayer(){
            const selected=form.querySelector('input[name="child_id"]:checked');
            if(newPlayerFields){
                newPlayerFields.classList.toggle('show',!selected || selected.value==='');
            }
        }
        function syncPayment(){
            const selected=form.querySelector('input[name="booking_payment_method"]:checked');
            if(packageSelect){
                packageSelect.classList.toggle('show',!!selected && selected.value==='package');
            }
        }

        form.querySelectorAll('input[name="child_id"]').forEach(function(input){input.addEventListener('change',syncPlayer)});
        form.querySelectorAll('input[name="booking_payment_method"]').forEach(function(input){input.addEventListener('change',syncPayment)});
        syncPlayer();
        syncPayment();
    });
});
</script>
@endsection
