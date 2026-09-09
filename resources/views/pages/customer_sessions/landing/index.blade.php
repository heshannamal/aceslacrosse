@extends('layouts.app')

@section('title', 'Training | Alcatraz Outlaws')

@section('content')
@php
    $landingSessions = collect($sessions ?? []);
    $calendarSessions = collect($calendarSessions ?? $landingSessions);
    $landingPackages = collect($packages ?? []);
    $children = collect($children ?? []);
    $isLoggedIn = !empty($customer);
    $remainingCredits = (int) ($remainingCredits ?? data_get($summary ?? [], 'remaining_credits', 0));

    $heroSlides = [
        [
            'type' => 'STICKWORK',
            'title' => 'Build the stick skills that separate great players.',
            'text' => 'Focused repetition for catching, throwing, control, confidence and game-ready mechanics.',
            'image' => asset('bay-area-lacrosse-academy-images/1.jpg'),
        ],
        [
            'type' => 'SPEEDWORK',
            'title' => 'Train faster movement, reactions and body control.',
            'text' => 'Acceleration, agility and movement training designed to transfer directly to lacrosse.',
            'image' => asset('bay-area-lacrosse-academy-images/2.jpg'),
        ],
        [
            'type' => 'FIELDWORK',
            'title' => 'Put stickwork and speedwork together on the field.',
            'text' => 'Apply skills in live situations with positioning, leverage, reads and decision-making.',
            'image' => asset('bay-area-lacrosse-academy-images/3.jpg'),
        ],
    ];

    $calendarEvents = $calendarSessions->map(function ($session) {
        if (!$session || empty($session->event_date)) {
            return null;
        }

        try {
            $date = \Carbon\Carbon::parse($session->event_date)->format('Y-m-d');
            $start = !empty($session->start_time)
                ? \Carbon\Carbon::parse($session->start_time)->format('g:i A')
                : '';
            $end = !empty($session->end_time)
                ? \Carbon\Carbon::parse($session->end_time)->format('g:i A')
                : '';

            return [
                'id' => (int) $session->id,
                'date' => $date,
                'title' => $session->training_type ?: ($session->name ?: 'Training'),
                'time' => trim($start . ($end ? ' - ' . $end : '')),
                'is_full' => (bool) ($session->is_full ?? false),
            ];
        } catch (\Throwable $e) {
            return null;
        }
    })->filter()->values()->all();

    $calendarEventsJson = json_encode(
        $calendarEvents,
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
    ) ?: '[]';

    $loginUrlJson = json_encode(
        route('em.customer.login', ['redirect' => route('em.customer.index')]),
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
    );
@endphp

<div class="outlaws-training-page">
    @if(session('success'))
        <div class="training-flash success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="training-flash error">{{ session('error') }}</div>
    @endif

    <section class="training-hero" id="trainingHero">
        @foreach($heroSlides as $slide)
            <article class="training-hero-slide {{ $loop->first ? 'active' : '' }}" data-hero-slide="{{ $loop->index }}">
                <img src="{{ $slide['image'] }}" alt="{{ $slide['type'] }} Training" class="training-hero-image">
                <div class="training-hero-overlay"></div>

                <div class="training-hero-inner">

                    <div class="training-hero-copy">
                        <span class="training-hero-tag">{{ $slide['type'] }}</span>
                        <h1>{{ $slide['title'] }}</h1>
                        <p>{{ $slide['text'] }}</p>
                            @if($isLoggedIn)
                                <a href="{{ route('em.customer.dashboard') }}" class="training-hero-login-btn">
                                    Training Dashboard <i class="fa-regular fa-user"></i>
                                </a>
                            @else
                                <a href="{{ route('em.customer.login', ['redirect' => route('em.customer.index')]) }}" class="training-hero-login-btn">
                                    Training Login <i class="fa-solid fa-arrow-right-to-bracket"></i>
                                </a>
                            @endif
                    </div>

                    <div class="training-hero-controls">
                        <div class="training-hero-dots">
                            @foreach($heroSlides as $dot)
                                <button type="button" class="training-hero-dot {{ $loop->first ? 'active' : '' }}" data-hero-dot="{{ $loop->index }}" aria-label="Show training slide {{ $loop->iteration }}"></button>
                            @endforeach
                        </div>
                        <button type="button" class="training-hero-arrow" data-hero-prev aria-label="Previous training slide"><i class="fa-solid fa-chevron-left"></i></button>
                        <button type="button" class="training-hero-arrow" data-hero-next aria-label="Next training slide"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </div>
            </article>
        @endforeach
    </section>

    <section class="training-sessions-section" id="trainingSessions">
        <div class="training-content-width">
            <div class="training-section-heading session-heading">
                <div>
                    <span class="training-script-heading">Book your</span>
                    <h2>TRAINING SESSIONS</h2>
                </div>
            </div>

            @if($landingSessions->isEmpty())
                <div class="training-empty-state">
                    <img src="{{ asset('images/logo.png') }}" alt="Alcatraz Outlaws">
                    <h3>No upcoming sessions yet</h3>
                    <p>Check back soon for new Alcatraz Outlaws Training dates.</p>
                </div>
            @else
                <div class="training-session-grid">
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
                            ])->map(function ($value) {
                                return trim((string) $value);
                            })->filter()->unique()->implode(', ');
                            $instructors = collect(explode(',', (string) ($session->instructor ?? '')))
                                ->map(function ($value) {
                                    return trim($value);
                                })->filter()->values();
                            $isFull = (bool) ($session->is_full ?? false);
                        @endphp

                        <article class="training-session-card">
                            <div class="training-session-card-top">
                                <div>
                                    <span>TRAINING TYPE</span>
                                    <h3>{{ $sessionTitle }}</h3>
                                </div>

                                @if($sessionDate)
                                    <div class="training-date-badge">
                                        <strong>{{ $sessionDate->format('d') }}</strong>
                                        <span>{{ strtoupper($sessionDate->format('M')) }}<small>{{ strtoupper($sessionDate->format('l')) }}</small></span>
                                    </div>
                                @endif
                            </div>

                            <div class="training-info-box">
                                <span>TIME</span>
                                <strong class="training-red-text">{{ $sessionStart }} - {{ $sessionEnd }}</strong>
                            </div>

                            <div class="training-info-box">
                                <span>LOCATION</span>
                                <strong><i class="fa-solid fa-location-dot"></i>{{ $location ?: 'Location coming soon' }}</strong>
                            </div>

                            <div class="training-info-box instructors-box">
                                <span>INSTRUCTORS</span>
                                <div class="training-instructor-list">
                                    @forelse($instructors as $instructor)
                                        <span class="training-instructor-chip"><i class="fa-regular fa-user"></i>{{ $instructor }}</span>
                                    @empty
                                        <span class="training-instructor-chip">TBA</span>
                                    @endforelse
                                </div>
                            </div>

                            <div class="training-session-actions">
                                <button type="button" class="training-outline-button" data-bs-toggle="modal" data-bs-target="#trainingDetails{{ $session->id }}">Details</button>

                                @if($isFull)
                                    <button type="button" class="training-red-button" disabled>Full</button>
                                @elseif(!$isLoggedIn)
                                    <a href="{{ route('em.customer.login', ['redirect' => route('em.customer.index')]) }}" class="training-red-button">Book</a>
                                @else
                                    <button type="button" class="training-red-button" data-bs-toggle="modal" data-bs-target="#trainingBook{{ $session->id }}">Book</button>
                                @endif
                            </div>
                        </article>

                        <div class="modal fade" id="trainingDetails{{ $session->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content training-modal-card">
                                    <div class="training-modal-header">
                                        <div class="training-modal-brand">
                                            <img src="{{ asset('images/logo.png') }}" alt="Alcatraz Outlaws">
                                            <div><small>TRAINING SESSION</small><h3>{{ $sessionTitle }}</h3></div>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="training-modal-body">
                                        <p class="training-modal-description">{{ $session->description ?: 'Focused Alcatraz Outlaws Training session.' }}</p>
                                        <div class="training-modal-details-grid">
                                            <div><span>Date</span><strong>{{ $sessionDate ? $sessionDate->format('l, F j, Y') : '-' }}</strong></div>
                                            <div><span>Time</span><strong>{{ $sessionStart }} - {{ $sessionEnd }}</strong></div>
                                            <div><span>Location</span><strong>{{ $location ?: '-' }}</strong></div>
                                            <div><span>Instructor(s)</span><strong>{{ $instructors->isNotEmpty() ? $instructors->implode(', ') : 'TBA' }}</strong></div>
                                        </div>
                                        @if(!empty($session->what_to_bring))
                                            <div class="training-what-to-bring">
                                                <span>WHAT TO BRING</span>
                                                <p>{{ $session->what_to_bring }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($isLoggedIn && !$isFull)
                            <div class="modal fade" id="trainingBook{{ $session->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content training-book-modal">
                                        <form method="POST" action="{{ route('em.customer.session.book', $session->id) }}" class="training-booking-form" data-session-id="{{ $session->id }}">
                                            @csrf
                                            <div class="training-book-modal-top">
                                                <div class="training-book-title"><img src="{{ asset('images/logo.png') }}" alt=""><span>ADD BOOKING</span></div>
                                                <button type="button" class="training-modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                            </div>

                                            <div class="training-book-steps">
                                                <div class="training-book-step active" data-step-label="1"><span>1</span>PLAYER</div>
                                                <div class="training-book-step" data-step-label="2"><span>2</span>PAYMENT</div>
                                                <div class="training-book-step" data-step-label="3"><span>3</span>CONFIRM</div>
                                            </div>

                                            <div class="training-book-body">
                                                <div class="training-book-pane active" data-book-pane="1">
                                                    <h4>Who is training?</h4>
                                                    <p>Select an existing player or add a new player for this booking.</p>

                                                    @if($children->isNotEmpty())
                                                        <div class="training-player-options">
                                                            @foreach($children as $child)
                                                                <label class="training-player-choice">
                                                                    <input type="radio" name="child_id" value="{{ $child->id }}" {{ $loop->first ? 'checked' : '' }}>
                                                                    <span class="training-player-choice-mark"><i class="fa-regular fa-user"></i></span>
                                                                    <span><strong>{{ trim($child->first_name . ' ' . $child->last_name) }}</strong><small>{{ collect([$child->team, $child->class_year, $child->position])->filter()->implode(' · ') }}</small></span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    <label class="training-player-choice add-new-player-choice">
                                                        <input type="radio" name="child_id" value="" data-new-player-radio {{ $children->isEmpty() ? 'checked' : '' }}>
                                                        <span class="training-player-choice-mark"><i class="fa-solid fa-user-plus"></i></span>
                                                        <span><strong>Add a new player</strong><small>Create the player while booking</small></span>
                                                    </label>

                                                    <div class="training-new-player-fields {{ $children->isEmpty() ? 'show' : '' }}" data-new-player-fields>
                                                        <div class="training-book-grid">
                                                            <label>First Name<input name="player_first" placeholder="First name"></label>
                                                            <label>Last Name<input name="player_last" placeholder="Last name"></label>
                                                        </div>
                                                        <div class="training-book-grid">
                                                            <label>Grad Year<input name="grad_year" type="number" min="2000" max="2100" placeholder="2032"></label>
                                                            <label>Position<input name="positions[]" placeholder="Attack, Middie, Defense, Goalie"></label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="training-book-pane" data-book-pane="2">
                                                    <h4>Choose payment</h4>
                                                    <p>Use an available class credit or purchase a Training package.</p>

                                                    @if($remainingCredits > 0)
                                                        <label class="training-payment-choice">
                                                            <input type="radio" name="booking_payment_method" value="credit" checked>
                                                            <span><strong>Use 1 Training Credit</strong><small>{{ $remainingCredits }} credit{{ $remainingCredits === 1 ? '' : 's' }} remaining</small></span>
                                                        </label>
                                                    @endif

                                                    <label class="training-payment-choice">
                                                        <input type="radio" name="booking_payment_method" value="package" {{ $remainingCredits < 1 ? 'checked' : '' }}>
                                                        <span><strong>Buy a Training Package</strong><small>Checkout is required before the booking is confirmed.</small></span>
                                                    </label>

                                                    <div class="training-package-select {{ $remainingCredits < 1 ? 'show' : '' }}" data-package-select>
                                                        <label>Package
                                                            <select name="selected_package_id">
                                                                <option value="">Select package</option>
                                                                @foreach($landingPackages as $package)
                                                                    <option value="{{ $package->id }}">
                                                                        {{ $package->package_name }} — ${{ number_format($package->priceForCustomer($customer), 2) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="training-book-pane" data-book-pane="3">
                                                    <div class="training-confirm-card">
                                                        <img src="{{ asset('images/logo.png') }}" alt="Alcatraz Outlaws">
                                                        <span>READY TO BOOK</span>
                                                        <h4>{{ $sessionTitle }}</h4>
                                                        <p>{{ $sessionDate ? $sessionDate->format('D, M j, Y') : '' }} · {{ $sessionStart }} - {{ $sessionEnd }}</p>
                                                        <p>{{ $location }}</p>
                                                    </div>
                                                    <div class="training-book-error" data-book-error></div>
                                                </div>
                                            </div>

                                            <div class="training-book-footer">
                                                <button type="button" class="training-book-back" data-book-back disabled><i class="fa-solid fa-arrow-left"></i> Back</button>
                                                <button type="button" class="training-book-next" data-book-next>Next <i class="fa-solid fa-arrow-right"></i></button>
                                                <button type="submit" class="training-book-submit" data-book-submit hidden>Confirm Booking <i class="fa-solid fa-check"></i></button>
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

    <section class="training-calendar-section" id="trainingCalendarSection">
        <div class="training-content-width">
            <div class="training-calendar-heading-row">
                <div class="training-section-heading calendar-heading">
                    <div>
                        <span class="training-script-heading">Training Session</span>
                        <h2>CALENDAR</h2>
                    </div>
                </div>

                <div class="training-calendar-nav">
                    <button type="button" id="trainingCalendarPrev" aria-label="Previous month"><i class="fa-solid fa-chevron-left"></i></button>
                    <strong id="trainingCalendarMonth"></strong>
                    <button type="button" id="trainingCalendarNext" aria-label="Next month"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>

            <div class="training-calendar-shell">
                <div class="training-calendar-weekdays">
                    <span>SUN</span><span>MON</span><span>TUE</span><span>WED</span><span>THU</span><span>FRI</span><span>SAT</span>
                </div>
                <div class="training-calendar-grid" id="trainingCalendarGrid"></div>
            </div>
        </div>
    </section>
</div>

<style>
    .outlaws-training-page{--red:#f3282c;--dark:#06101f;--muted:#667085;background:#fff;color:#050505;font-family:'Raleway','Karla',Arial,sans-serif}
    .training-content-width{width:min(1120px,calc(100% - 40px));margin:0 auto}
    .training-flash{width:min(1120px,calc(100% - 40px));margin:18px auto 0;padding:13px 17px;border-radius:12px;font-weight:800}.training-flash.success{background:#ecfdf3;color:#166534;border:1px solid #bbf7d0}.training-flash.error{background:#fff1f2;color:#991b1b;border:1px solid #fecdd3}

    .training-hero{position:relative;height:650px;overflow:hidden;background:#050505}.training-hero-slide{position:absolute;inset:0;opacity:0;pointer-events:none;transition:opacity .7s ease}.training-hero-slide.active{opacity:1;pointer-events:auto}.training-hero-image{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}.training-hero-overlay{position:absolute;inset:0;background:linear-gradient(90deg,rgba(3,8,14,.92) 0%,rgba(3,8,14,.72) 44%,rgba(3,8,14,.25) 72%,rgba(3,8,14,.15) 100%)}
    .training-hero-inner{position:relative;z-index:2;width:min(1120px,calc(100% - 40px));height:100%;margin:0 auto;padding:34px 0 40px;display:flex;flex-direction:column}.training-hero-topline{display:flex;justify-content:space-between;align-items:center;gap:20px}.training-hero-logo{width:220px;max-height:92px;object-fit:contain;object-position:left center;filter:drop-shadow(0 3px 10px rgba(0,0,0,.35))}.training-hero-login-btn{display:inline-flex;align-items:center;gap:9px;padding:12px 18px;border:1px solid rgba(255,255,255,.65);border-radius:999px;background:rgba(0,0,0,.3);backdrop-filter:blur(7px);color:#fff!important;font-size:13px;font-weight:900;text-decoration:none}.training-hero-login-btn:hover{background:var(--red);border-color:var(--red)}
    .training-hero-copy{max-width:630px;margin:auto 0 56px}.training-hero-tag{display:inline-flex;padding:8px 17px;border:1px solid rgba(243,40,44,.85);border-radius:999px;background:rgba(7,16,31,.44);color:#fff;font-size:14px;font-weight:900;letter-spacing:.2em}.training-hero-copy h1{margin:20px 0 14px;color:#fff;font-family:'Archivo Black',Arial,sans-serif;font-size:clamp(42px,5.1vw,68px);line-height:.98;letter-spacing:-.055em}.training-hero-copy p{max-width:560px;margin:0 0 24px;color:#f3f4f6;font-size:17px;line-height:1.55;font-weight:600}.training-hero-cta{display:inline-flex;align-items:center;gap:10px;padding:14px 20px;background:var(--red);border-radius:999px;color:#fff!important;font-weight:900;text-decoration:none;box-shadow:0 12px 26px rgba(243,40,44,.28)}
    .training-hero-controls{position:absolute;right:0;bottom:38px;display:flex;align-items:center;gap:8px}.training-hero-dots{display:flex;gap:7px;margin-right:8px}.training-hero-dot{width:29px;height:5px;border:0;border-radius:99px;background:rgba(255,255,255,.48)}.training-hero-dot.active{background:var(--red)}.training-hero-arrow{width:38px;height:38px;border-radius:50%;border:1px solid rgba(255,255,255,.5);background:rgba(0,0,0,.34);color:#fff}.training-hero-arrow:hover{background:var(--red);border-color:var(--red)}

    .training-sessions-section{padding:64px 0 74px;background:linear-gradient(180deg,#fff 0%,#fff 72%,#fafafa 100%)}.training-section-heading{display:flex;align-items:center;gap:14px;margin-bottom:34px}.training-heading-brand{width:48px;height:48px;display:flex;align-items:center;justify-content:center}.training-heading-brand img{max-width:100%;max-height:100%;object-fit:contain}.training-script-heading{display:block;color:var(--red);font-family:'Caveat',cursive;font-size:41px;font-weight:800;line-height:.8}.training-section-heading h2{margin:5px 0 0;color:var(--dark);font-family:'Archivo Black',Arial,sans-serif;font-size:36px;letter-spacing:-.04em;line-height:1}
    .training-session-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:20px;align-items:start}.training-session-card{padding:18px 15px 14px;border:1px solid #dfe3e8;border-radius:24px;background:#fff;box-shadow:0 10px 22px rgba(15,23,42,.04)}.training-session-card-top{min-height:74px;padding:0 4px 13px;display:flex;justify-content:space-between;align-items:flex-start;gap:12px;border-bottom:1px solid #ebedf0}.training-session-card-top span,.training-info-box>span{display:block;color:#667085;font-size:10px;font-weight:900;letter-spacing:.15em}.training-session-card-top h3{margin:5px 0 0;color:#08101e;font-size:26px;line-height:1;font-weight:700}.training-date-badge{min-width:104px;height:52px;padding:0 11px;border:1px solid #dfe3e8;border-radius:18px;display:flex;align-items:center;justify-content:center;gap:10px;background:#fff;box-shadow:0 3px 8px rgba(15,23,42,.04)}.training-date-badge strong{font-size:30px;line-height:1;font-weight:500}.training-date-badge span{color:var(--red);font-size:10px;line-height:1.15;font-weight:900;letter-spacing:.14em}.training-date-badge small{display:block;color:#101828;font-size:8px;letter-spacing:.03em;margin-top:2px}
    .training-info-box{margin-top:12px;padding:13px 14px;border-radius:17px;background:#f0f1f3}.training-info-box strong{display:flex;align-items:center;gap:8px;margin-top:7px;color:#050505;font-size:13px;line-height:1.35}.training-info-box strong i{color:var(--red)}.training-red-text{color:var(--red)!important;font-size:16px!important;font-weight:500!important}.training-instructor-list{display:flex;flex-wrap:wrap;gap:7px;margin-top:8px}.training-instructor-chip{display:inline-flex;align-items:center;gap:6px;padding:7px 10px;border-radius:999px;border:1px solid #e1e4e8;background:#fff;font-size:11px;font-weight:800}.training-instructor-chip i{color:var(--red)}
    .training-session-actions{display:grid;grid-template-columns:1fr 1fr;gap:9px;margin-top:15px}.training-outline-button,.training-red-button{min-height:43px;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;border:1.5px solid #050505;font-size:13px;font-weight:900;text-decoration:none;cursor:pointer}.training-outline-button{background:#fff;color:#050505}.training-outline-button:hover{background:#050505;color:#fff}.training-red-button{border-color:var(--red);background:var(--red);color:#fff!important}.training-red-button:hover:not(:disabled){background:#d91f23;border-color:#d91f23}.training-red-button:disabled{opacity:.55;cursor:not-allowed}
    .training-empty-state{padding:60px 24px;border:1px solid #e3e6ea;border-radius:24px;text-align:center;background:#fff}.training-empty-state img{width:120px;max-height:70px;object-fit:contain;margin-bottom:15px}.training-empty-state h3{font-weight:900}

    .training-calendar-section{padding:58px 0 82px;background:#f0f1f3}.training-calendar-heading-row{display:flex;align-items:center;justify-content:space-between;gap:24px}.training-calendar-heading-row .training-section-heading{margin-bottom:26px}.training-calendar-nav{display:flex;align-items:center;gap:18px;margin-bottom:24px}.training-calendar-nav button{width:38px;height:38px;border-radius:50%;border:1px solid #dfe3e8;background:#fff;color:#07101f}.training-calendar-nav strong{min-width:165px;text-align:center;font-size:26px;font-weight:500}.training-calendar-shell{overflow:hidden;border-radius:24px;background:#fff;box-shadow:0 16px 34px rgba(15,23,42,.08)}.training-calendar-weekdays{display:grid;grid-template-columns:repeat(7,1fr);background:#050505;color:#fff}.training-calendar-weekdays span{padding:14px 6px;text-align:center;font-size:10px;font-weight:900;letter-spacing:.18em}.training-calendar-grid{display:grid;grid-template-columns:repeat(7,1fr)}.training-calendar-cell{position:relative;min-height:122px;padding:13px 12px;border-right:1px solid #e2e5e9;border-bottom:1px solid #e2e5e9;background:#fff}.training-calendar-cell:nth-child(7n){border-right:0}.training-calendar-cell.other-month{background:#f7f8fa;color:#9aa4b2}.training-calendar-cell.today{background:linear-gradient(180deg,#fff5f5,#fff)}.training-calendar-number{display:inline-flex;align-items:center;justify-content:center;min-width:27px;height:27px;font-size:13px;font-weight:900}.training-calendar-cell.today .training-calendar-number{border-radius:50%;background:var(--red);color:#fff}.training-calendar-event{margin-top:7px;padding:8px;border:1px solid #f4c4c5;border-radius:10px;background:#fff8f8}.training-calendar-event strong{display:block;font-size:11px}.training-calendar-event span{display:block;margin-top:2px;color:#5e6673;font-size:9px;font-weight:700}.training-calendar-event a{display:inline-flex;margin-top:6px;padding:4px 10px;border-radius:999px;background:var(--red);color:#fff!important;font-size:8px;font-weight:900;text-decoration:none}.training-calendar-event.full{opacity:.6}.training-calendar-event.full a{background:#9ca3af;pointer-events:none}

    .training-modal-card,.training-book-modal{border:0;border-radius:22px;overflow:hidden;box-shadow:0 25px 65px rgba(0,0,0,.22)}.training-modal-header{padding:18px 22px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #e5e7eb}.training-modal-brand{display:flex;align-items:center;gap:13px}.training-modal-brand img{width:70px;max-height:48px;object-fit:contain}.training-modal-brand small{color:var(--red);font-size:10px;font-weight:900;letter-spacing:.13em}.training-modal-brand h3{margin:2px 0 0;font-size:23px;font-weight:900}.training-modal-body{padding:24px}.training-modal-description{color:#4b5563;font-size:15px;line-height:1.6}.training-modal-details-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}.training-modal-details-grid>div{padding:13px;border-radius:13px;background:#f4f5f7}.training-modal-details-grid span{display:block;color:#667085;font-size:9px;font-weight:900;letter-spacing:.13em;text-transform:uppercase}.training-modal-details-grid strong{display:block;margin-top:4px;font-size:13px}.training-what-to-bring{margin-top:14px;padding:15px;border:1px solid #f3c4c5;border-radius:14px;background:#fff7f7}.training-what-to-bring span{color:var(--red);font-size:10px;font-weight:900;letter-spacing:.12em}.training-what-to-bring p{margin:5px 0 0}

    .training-book-modal-top{padding:15px 18px;background:#fff;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #e7e9ed}.training-book-title{display:flex;align-items:center;gap:9px;color:var(--red);font-size:11px;font-weight:900;letter-spacing:.12em}.training-book-title img{width:55px;max-height:38px;object-fit:contain}.training-modal-close{width:36px;height:36px;border:0;border-radius:50%;background:#07101f;color:#fff}.training-book-steps{padding:10px 18px;background:#fff8f8;display:grid;grid-template-columns:repeat(3,1fr);gap:8px}.training-book-step{padding:9px 12px;border-radius:999px;background:#f0f2f4;color:#6b7280;font-size:9px;font-weight:900;letter-spacing:.08em}.training-book-step span{display:inline-flex;width:23px;height:23px;align-items:center;justify-content:center;margin-right:6px;border-radius:50%;background:#fff;color:#111827}.training-book-step.active{background:#07101f;color:#fff}.training-book-step.active span{background:var(--red);color:#fff}.training-book-body{min-height:340px;padding:24px}.training-book-pane{display:none}.training-book-pane.active{display:block}.training-book-pane h4{margin:0;font-size:24px;font-weight:900}.training-book-pane>p{color:#667085}.training-player-options{display:grid;gap:8px}.training-player-choice,.training-payment-choice{display:flex;align-items:center;gap:11px;padding:13px;border:1px solid #e1e4e8;border-radius:14px;cursor:pointer}.training-player-choice:has(input:checked),.training-payment-choice:has(input:checked){border-color:var(--red);background:#fff7f7}.training-player-choice input,.training-payment-choice input{accent-color:var(--red)}.training-player-choice-mark{width:38px;height:38px;display:flex;align-items:center;justify-content:center;border-radius:11px;background:#fff0f0;color:var(--red)}.training-player-choice strong,.training-payment-choice strong{display:block;font-size:13px}.training-player-choice small,.training-payment-choice small{display:block;color:#667085;font-size:10px;margin-top:2px}.add-new-player-choice{margin-top:8px}.training-new-player-fields,.training-package-select{display:none;margin-top:12px;padding:14px;border-radius:14px;background:#f7f8fa}.training-new-player-fields.show,.training-package-select.show{display:block}.training-book-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px}.training-book-grid:last-child{margin-bottom:0}.training-book-grid label,.training-package-select label{font-size:11px;font-weight:800;color:#475467}.training-book-grid input,.training-package-select select{display:block;width:100%;height:43px;margin-top:5px;padding:0 11px;border:1px solid #d9dee7;border-radius:10px;background:#fff;outline:0}.training-payment-choice{margin-bottom:9px}.training-confirm-card{text-align:center;padding:26px;border:1px solid #e2e5e9;border-radius:18px;background:#fbfbfc}.training-confirm-card img{width:110px;max-height:62px;object-fit:contain}.training-confirm-card>span{display:block;margin-top:12px;color:var(--red);font-size:10px;font-weight:900;letter-spacing:.15em}.training-confirm-card h4{margin-top:7px}.training-confirm-card p{margin:5px 0;color:#667085}.training-book-error{display:none;margin-top:12px;padding:11px;border-radius:10px;background:#fff1f2;color:#991b1b;font-size:12px;font-weight:700}.training-book-error.show{display:block}.training-book-footer{padding:14px 18px;display:flex;justify-content:space-between;gap:10px;border-top:1px solid #e6e8eb}.training-book-back,.training-book-next,.training-book-submit{min-width:110px;height:43px;border-radius:999px;font-weight:900}.training-book-back{border:1px solid #dfe3e8;background:#fff}.training-book-next,.training-book-submit{border:0;background:var(--red);color:#fff}.training-book-back:disabled{opacity:.4}

    @media(max-width:991px){.training-hero{height:600px}.training-session-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.training-calendar-cell{min-height:108px}.training-heading-brand{display:none}}
    @media(max-width:720px){.training-content-width,.training-hero-inner{width:min(100% - 24px,1120px)}.training-hero{height:560px}.training-hero-logo{width:160px}.training-hero-login-btn{padding:10px 13px;font-size:11px}.training-hero-copy{margin-bottom:62px}.training-hero-copy h1{font-size:42px}.training-hero-copy p{font-size:14px}.training-session-grid{grid-template-columns:1fr}.training-calendar-heading-row{align-items:flex-start;flex-direction:column}.training-calendar-nav{width:100%;justify-content:flex-end}.training-calendar-shell{overflow-x:auto}.training-calendar-weekdays,.training-calendar-grid{min-width:780px}.training-modal-details-grid,.training-book-grid{grid-template-columns:1fr}.training-book-steps{grid-template-columns:1fr}.training-book-step{display:none}.training-book-step.active{display:block}}
</style>

<script>
document.addEventListener('DOMContentLoaded',function(){
    const slides=Array.from(document.querySelectorAll('[data-hero-slide]'));
    const dots=Array.from(document.querySelectorAll('[data-hero-dot]'));
    let heroIndex=0;
    let heroTimer=null;

    function showHero(index){
        if(!slides.length)return;
        heroIndex=(index+slides.length)%slides.length;
        slides.forEach((slide,i)=>slide.classList.toggle('active',i===heroIndex));
        dots.forEach((dot,i)=>dot.classList.toggle('active',i===heroIndex));
    }
    function restartHero(){clearInterval(heroTimer);heroTimer=setInterval(()=>showHero(heroIndex+1),6500)}
    document.querySelector('[data-hero-prev]')?.addEventListener('click',()=>{showHero(heroIndex-1);restartHero()});
    document.querySelector('[data-hero-next]')?.addEventListener('click',()=>{showHero(heroIndex+1);restartHero()});
    dots.forEach((dot,i)=>dot.addEventListener('click',()=>{showHero(i);restartHero()}));
    restartHero();

    const calendarEvents={!! $calendarEventsJson !!};
    const loginUrl={!! $loginUrlJson !!};
    const monthLabel=document.getElementById('trainingCalendarMonth');
    const grid=document.getElementById('trainingCalendarGrid');
    const now=new Date();
    let calendarDate=new Date(now.getFullYear(),now.getMonth(),1);

    if(calendarEvents.length){
        const firstEventDate=new Date(calendarEvents[0].date+'T12:00:00');
        if(firstEventDate.getFullYear()!==now.getFullYear() || firstEventDate.getMonth()!==now.getMonth()){
            calendarDate=new Date(firstEventDate.getFullYear(),firstEventDate.getMonth(),1);
        }
    }

    function escapeHtml(value){return String(value??'').replace(/[&<>'"]/g,function(char){return({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'})[char]})}
    function dateKey(date){return date.getFullYear()+'-'+String(date.getMonth()+1).padStart(2,'0')+'-'+String(date.getDate()).padStart(2,'0')}
    function renderCalendar(){
        if(!grid||!monthLabel)return;
        monthLabel.textContent=calendarDate.toLocaleDateString('en-US',{month:'long',year:'numeric'});
        grid.innerHTML='';
        const year=calendarDate.getFullYear();
        const month=calendarDate.getMonth();
        const first=new Date(year,month,1);
        const start=new Date(year,month,1-first.getDay());
        const todayKey=dateKey(now);

        for(let i=0;i<42;i++){
            const day=new Date(start);day.setDate(start.getDate()+i);
            const key=dateKey(day);
            const cell=document.createElement('div');
            cell.className='training-calendar-cell'+(day.getMonth()!==month?' other-month':'')+(key===todayKey?' today':'');
            cell.innerHTML='<span class="training-calendar-number">'+day.getDate()+'</span>';
            calendarEvents.filter(event=>event.date===key).forEach(function(event){
                const item=document.createElement('div');
                item.className='training-calendar-event'+(event.is_full?' full':'');
                let action='';
                if(event.is_full){
                    action='<a href="#">FULL</a>';
                }else{
                    @if($isLoggedIn)
                        action='<a href="#" data-calendar-book="'+event.id+'">BOOK</a>';
                    @else
                        action='<a href="'+loginUrl+'">LOGIN TO BOOK</a>';
                    @endif
                }
                item.innerHTML='<strong>'+escapeHtml(event.title)+'</strong><span>'+escapeHtml(event.time)+'</span>'+action;
                cell.appendChild(item);
            });
            grid.appendChild(cell);
        }

        grid.querySelectorAll('[data-calendar-book]').forEach(function(link){
            link.addEventListener('click',function(event){
                event.preventDefault();
                const id=link.getAttribute('data-calendar-book');
                const modal=document.getElementById('trainingBook'+id);
                if(modal&&window.bootstrap){new bootstrap.Modal(modal).show()}
            });
        });
    }

    document.getElementById('trainingCalendarPrev')?.addEventListener('click',function(){calendarDate=new Date(calendarDate.getFullYear(),calendarDate.getMonth()-1,1);renderCalendar()});
    document.getElementById('trainingCalendarNext')?.addEventListener('click',function(){calendarDate=new Date(calendarDate.getFullYear(),calendarDate.getMonth()+1,1);renderCalendar()});
    renderCalendar();

    document.querySelectorAll('.training-booking-form').forEach(function(form){
        let step=1;
        const panes=Array.from(form.querySelectorAll('[data-book-pane]'));
        const labels=Array.from(form.querySelectorAll('[data-step-label]'));
        const back=form.querySelector('[data-book-back]');
        const next=form.querySelector('[data-book-next]');
        const submit=form.querySelector('[data-book-submit]');
        const newFields=form.querySelector('[data-new-player-fields]');
        const packageSelect=form.querySelector('[data-package-select]');
        const errorBox=form.querySelector('[data-book-error]');

        function renderStep(){
            panes.forEach(pane=>pane.classList.toggle('active',Number(pane.dataset.bookPane)===step));
            labels.forEach(label=>label.classList.toggle('active',Number(label.dataset.stepLabel)===step));
            back.disabled=step===1;
            next.hidden=step===3;
            submit.hidden=step!==3;
        }

        function refreshNewPlayer(){
            const checked=form.querySelector('input[name="child_id"]:checked');
            if(newFields)newFields.classList.toggle('show',!checked||checked.value==='');
        }
        function refreshPackage(){
            const method=form.querySelector('input[name="booking_payment_method"]:checked')?.value;
            if(packageSelect)packageSelect.classList.toggle('show',method==='package');
        }

        form.querySelectorAll('input[name="child_id"]').forEach(input=>input.addEventListener('change',refreshNewPlayer));
        form.querySelectorAll('input[name="booking_payment_method"]').forEach(input=>input.addEventListener('change',refreshPackage));
        refreshNewPlayer();refreshPackage();renderStep();

        next.addEventListener('click',function(){
            if(step===1){
                const child=form.querySelector('input[name="child_id"]:checked');
                const playerFirst=form.querySelector('input[name="player_first"]');
                if((!child||child.value==='') && (!playerFirst||!playerFirst.value.trim())){alert('Please select a player or enter a new player first name.');return}
            }
            if(step===2){
                const method=form.querySelector('input[name="booking_payment_method"]:checked')?.value;
                const selectedPackage=form.querySelector('select[name="selected_package_id"]')?.value;
                if(method==='package'&&!selectedPackage){alert('Please select a Training package.');return}
            }
            step=Math.min(3,step+1);renderStep();
        });
        back.addEventListener('click',function(){step=Math.max(1,step-1);renderStep()});

        form.addEventListener('submit',async function(event){
            event.preventDefault();
            if(errorBox){errorBox.classList.remove('show');errorBox.textContent=''}
            submit.disabled=true;
            const original=submit.innerHTML;
            submit.innerHTML='Booking...';
            try{
                const response=await fetch(form.action,{method:'POST',body:new FormData(form),headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}});
                const data=await response.json().catch(()=>({}));
                if(!response.ok||!data.status){throw new Error(data.message||'Booking could not be completed.')}
                window.location.href=data.redirect||'{{ route('em.customer.bookings') }}';
            }catch(error){
                if(errorBox){errorBox.textContent=error.message;errorBox.classList.add('show')}else{alert(error.message)}
            }finally{submit.disabled=false;submit.innerHTML=original}
        });
    });
});
</script>
@endsection
