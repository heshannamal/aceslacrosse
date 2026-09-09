@extends('admin.layouts.app')

@section('title', request()->routeIs('admin.em.sessions.create') ? 'Create Session' : 'Edit Session')

@section('content')
@php
    $session = $session ?? null;
    $selectedTraining = old('training_type', optional($session)->training_type ?: 'Stickwork');
    $instructorValue = old('instructor', optional($session)->instructor ?: '');
    $locationValue = old('location', optional($session)->location);
    $streetAddressValue = old('street_address', optional($session)->street_address);
    $cityValue = old('city', optional($session)->city);
    $capacityValue = old('capacity', optional($session)->capacity ?: 10);
    $latValue = old('location_lat', optional($session)->location_lat);
    $lngValue = old('location_lng', optional($session)->location_lng);

    $defaultTrainingDescriptions = [
        'Stickwork' => 'Alcatraz stickwork training is done on rebound walls to isolate the mechanics of catching and throwing away from the more traditional athletic and competitive elements on the lacrosse field. Starting with proper grip and stance, to body mechanics and form, and up to higher level techniques.',
        'Fieldwork' => 'Fieldwork is where stickwork and speedwork come together in game situations. Players learn how to apply their skills to create space and win 1v1 matchups, becoming consistent scoring threats. On both offense and defense, the focus is on positioning, leverage, and decision-making at full speed.',
        'Speedwork' => 'Speedwork is led by professional speed and agility coaches focused on building true athleticism. Beyond strength and speed, players learn movement mechanics, body control, and coordination. We emphasize how players move—not just how fast—creating a foundation that carries into every aspect of the game.',
    ];

    $defaultWhatToBring = [
        'Stickwork' => 'Sneakers, Lacrosse Stick, Goggles, Water',
        'Fieldwork' => 'Stick, Goggles, Cleats, Training Shoes, Water',
        'Speedwork' => 'Wear running shoes AND bring Cleats, Water bottle',
    ];

    $isEditing = !empty($session);
    $hasOldDescription = old('description') !== null;
    $descriptionValue = old('description');

    if (!$hasOldDescription) {
        $descriptionValue = optional($session)->description;
    }

    if (!$hasOldDescription && !$isEditing && ($descriptionValue === null || trim((string) $descriptionValue) === '')) {
        $descriptionValue = $defaultTrainingDescriptions[$selectedTraining] ?? '';
    }

    $hasOldWhatToBring = old('what_to_bring') !== null;
    $whatToBringValue = old('what_to_bring');

    if (!$hasOldWhatToBring) {
        $whatToBringValue = optional($session)->what_to_bring;
    }

    if (!$hasOldWhatToBring && !$isEditing && ($whatToBringValue === null || trim((string) $whatToBringValue) === '')) {
        $whatToBringValue = $defaultWhatToBring[$selectedTraining] ?? '';
    }

    $instructorList = old('instructors');

    if (!is_array($instructorList)) {
        $instructorList = collect(explode(',', $instructorValue))
            ->map(function ($name) {
                return trim($name);
            })
            ->filter()
            ->values()
            ->toArray();
    }

    if (empty($instructorList)) {
        $instructorList = [''];
    }
@endphp

<style>
    :root {
        --ao-primary: #f3282c;
        --ao-primary-dark: #d91f23;
        --ao-primary-soft: #fff0f1;
        --ao-bg: #f4f6fb;
        --ao-border: #dce3ee;
        --ao-muted: #64748b;
    }

    .bd-form-page {
        background: var(--ao-bg);
        min-height: calc(100vh - 70px);
    }

    .bd-form-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, .06);
        overflow: hidden;
        margin: 0;
        width: 100%;
    }

    .bd-form-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        flex-wrap: wrap;
        padding: 22px 26px;
        background: linear-gradient(90deg, #fff, #fff7f7 65%, #ffe9ea);
        border-bottom: 1px solid #edf1f6;
    }

    .bd-form-body {
        padding: 24px 26px 26px;
    }

    .bd-page-title {
        font-size: 24px;
        font-weight: 900;
        text-align: left;
        margin: 0;
        color: #06112b;
        letter-spacing: .01em;
    }

    .bd-page-subtitle {
        margin: 7px 0 0;
        color: #52627a;
        font-size: 13px;
        font-weight: 700;
    }

    .bd-label {
        display: block;
        margin-bottom: 8px;
        color: #111827;
        font-size: 13px;
        font-weight: 900;
    }

    .required {
        color: var(--ao-primary);
    }

    .bd-input {
        height: 46px;
        border-radius: 12px;
        border: 1px solid var(--ao-border);
        color: #111827;
        font-weight: 700;
        background: #fff;
    }

    .bd-input:focus,
    .bd-area:focus {
        box-shadow: 0 0 0 4px rgba(243, 40, 44, .12);
        border-color: var(--ao-primary);
    }

    .bd-area {
        border-radius: 12px;
        border: 1px solid var(--ao-border);
        color: #111827;
        font-weight: 700;
        min-height: 88px;
    }

    .bd-input::placeholder,
    .bd-area::placeholder {
        color: #6b7280;
        font-weight: 700;
    }

    .bd-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 44px;
        border-radius: 14px;
        font-weight: 900;
        padding: 12px 24px;
        text-decoration: none;
        border: 0;
        cursor: pointer;
        transition: .16s ease;
    }

    .bd-btn:hover {
        text-decoration: none;
        transform: translateY(-1px);
    }

    .bd-btn-primary {
        background: linear-gradient(135deg, var(--ao-primary), var(--ao-primary-dark));
        color: #fff;
    }

    .bd-btn-primary:hover {
        color: #fff;
    }

    .bd-btn-outline {
        border: 1px solid #d8dee9;
        background: #fff;
        color: #111827;
    }

    .bd-btn-outline:hover {
        background: #f8fafc;
        color: #111827;
    }

    .bd-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 18px;
        margin-top: 36px;
        flex-wrap: wrap;
    }

    .training-card {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 14px;
        cursor: pointer;
        transition: .18s;
        background: #fff;
        height: 100%;
    }

    .training-card:hover {
        border-color: var(--ao-primary);
        box-shadow: 0 10px 24px rgba(243, 40, 44, .10);
    }

    .training-card.active {
        border-color: var(--ao-primary);
        background: var(--ao-primary-soft);
        box-shadow: 0 0 0 4px rgba(243, 40, 44, .10);
    }

    .training-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--ao-primary), var(--ao-primary-dark));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
    }

    .location-suggestions {
        position: absolute;
        z-index: 30;
        left: 0;
        right: 0;
        top: 100%;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, .15);
        max-height: 260px;
        overflow: auto;
        display: none;
        margin-top: 6px;
    }

    .location-suggestion {
        padding: 11px 14px;
        cursor: pointer;
        border-bottom: 1px solid #f1f5f9;
        font-weight: 700;
    }

    .location-suggestion:hover {
        background: var(--ao-primary-soft);
        color: var(--ao-primary);
    }

    .location-suggestion:last-child {
        border-bottom: 0;
    }

    .map-box {
        height: 260px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        background: #f8fafc;
    }

    .map-box iframe {
        width: 100%;
        height: 100%;
        border: 0;
    }

    .map-help {
        font-size: 12px;
        color: #64748b;
        margin-top: 6px;
        font-weight: 700;
    }

    .input-group-text {
        border-color: var(--ao-border);
        font-weight: 900;
    }

    .input-group .bd-input {
        border-left: 0;
    }

    .input-group .btn {
        border-radius: 0 12px 12px 0;
        font-weight: 900;
        color: var(--ao-primary);
        border-color: var(--ao-primary);
        background: #fff;
    }

    .input-group .btn:hover {
        color: #fff;
        background: var(--ao-primary);
    }

    .instructor-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .instructor-row {
        display: grid;
        grid-template-columns: 1fr 46px;
        gap: 10px;
        align-items: center;
    }

    .instructor-input-wrap {
        position: relative;
    }

    .instructor-input-wrap .instructor-input {
        padding-left: 46px;
    }

    .instructor-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 24px;
        height: 24px;
        border-radius: 999px;
        background: var(--ao-primary-soft);
        color: var(--ao-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        z-index: 2;
    }

    .instructor-add-btn {
        border: 0;
        background: linear-gradient(135deg, var(--ao-primary), var(--ao-primary-dark));
        color: #fff;
        border-radius: 999px;
        padding: 9px 16px;
        font-size: 13px;
        font-weight: 900;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        box-shadow: 0 10px 22px rgba(243, 40, 44, .18);
    }

    .instructor-remove-btn {
        width: 46px;
        height: 46px;
        border: 0;
        border-radius: 14px;
        background: #fee2e2;
        color: #dc2626;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: .18s ease;
    }

    .instructor-remove-btn:hover {
        background: #dc2626;
        color: #fff;
    }

    .bd-info-box {
        padding: 18px;
        background: #fff7f7;
        border: 1px solid #f4c7c9;
        border-radius: 18px;
        color: #475569;
        font-weight: 700;
    }

    .bd-info-box i {
        color: var(--ao-primary) !important;
    }

    @media (max-width: 767px) {
        .bd-form-head,
        .bd-form-body {
            padding: 20px;
        }

        .bd-page-title {
            font-size: 22px;
        }

        .bd-form-actions .bd-btn {
            width: 100%;
        }

        .instructor-row {
            grid-template-columns: 1fr 42px;
        }

        .instructor-remove-btn {
            width: 42px;
            height: 42px;
        }
    }
</style>

<div class="bd-form-page">
    <form class="bd-form-card" method="POST" action="{{ $session ? route('admin.em.sessions.update', $session) : route('admin.em.sessions.store') }}" enctype="multipart/form-data">
        @csrf
        @if($session)
            @method('PUT')
        @endif

        <div class="bd-form-head">
            <div>
                <h3 class="bd-page-title">{{ $session ? 'Edit Event Session' : 'Create Event Session' }}</h3>
                <p class="bd-page-subtitle">Create a class/session with training type, instructor, location, date and time.</p>
            </div>

            <a href="{{ route('admin.em.sessions.index') }}" class="bd-btn bd-btn-outline">Back</a>
        </div>

        <div class="bd-form-body">
            @if($errors->any())
                <div class="alert alert-danger rounded-3 mb-4" role="alert">
                    <div class="fw-bold mb-2">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>Please correct the following errors:
                    </div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger rounded-3 mb-4" role="alert">{{ session('error') }}</div>
            @endif

            <input type="hidden" name="name" id="sessionNameInput" value="{{ old('name', optional($session)->name ?: '') }}">

            <div class="row g-4">
                <div class="col-12">
                    <label class="bd-label">Type of Training <span class="required">*</span></label>
                    <input type="hidden" name="training_type" id="trainingTypeInput" value="{{ $selectedTraining }}">

                    <div class="row g-3">
                        @foreach(['Stickwork' => 'fa-hockey-puck', 'Speedwork' => 'fa-bolt', 'Fieldwork' => 'fa-person-running'] as $trainingName => $icon)
                            <div class="col-md-4">
                                <div class="training-card {{ $selectedTraining === $trainingName ? 'active' : '' }}" data-training="{{ $trainingName }}">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="training-icon"><i class="fa-solid {{ $icon }}"></i></div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $trainingName }}</div>
                                            <div class="small text-muted fw-semibold">
                                                {{ $trainingName === 'Stickwork' ? 'Technical skills' : ($trainingName === 'Speedwork' ? 'Speed and agility' : 'Field drills') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('training_type')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-7">
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="bd-label">Description</label>
                            <textarea name="description" id="descriptionTextarea" class="form-control bd-area @error('description') is-invalid @enderror" rows="4" placeholder="Enter description">{{ $descriptionValue }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="bd-label">Documentation <small class="text-muted fw-semibold">(Optional PDF)</small></label>
                            <input type="file" name="documentation" class="form-control @error('documentation') is-invalid @enderror" accept=".pdf,application/pdf">
                            <small class="text-muted fw-semibold d-block mt-1">Upload training notes, drills, instructions or other documentation. PDF files only. Maximum size: 20 MB.</small>
                            @error('documentation')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                            @if(!empty(optional($session)->documentation))
                                <div class="mt-2">
                                    <a href="{{ asset('storage/'.$session->documentation) }}" target="_blank" class="btn btn-sm btn-outline-danger fw-bold">
                                        <i class="fa-solid fa-file-pdf"></i>
                                        View Current PDF
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="bd-label">Street Address</label>
                            <input type="text" name="street_address" class="form-control bd-input @error('street_address') is-invalid @enderror" value="{{ $streetAddressValue }}" placeholder="Enter street address">
                            @error('street_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="bd-label">City</label>
                            <input type="text" name="city" class="form-control bd-input @error('city') is-invalid @enderror" value="{{ $cityValue }}" placeholder="Enter city">
                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="bd-label">Location <span class="required">*</span></label>
                            <div class="position-relative">
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="fa-solid fa-location-dot" style="color:#f3282c"></i>
                                    </span>
                                    <input type="text" name="location" id="locationInput" class="form-control bd-input @error('location') is-invalid @enderror" value="{{ $locationValue }}" placeholder="Search location" autocomplete="off">
                                    <button type="button" class="btn btn-outline-primary" id="previewMapBtn">Preview</button>
                                </div>
                                <div class="location-suggestions" id="locationSuggestions"></div>
                            </div>
                            @error('location')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            <div class="map-help">Start typing to search locations. Latitude and longitude are saved automatically and hidden.</div>
                            <input type="hidden" name="location_lat" id="locationLat" value="{{ $latValue }}">
                            <input type="hidden" name="location_lng" id="locationLng" value="{{ $lngValue }}">
                        </div>

                        <div class="col-12">
                            <div class="map-box">
                                <iframe id="mapFrame" loading="lazy" src="https://maps.google.com/maps?q={{ urlencode($locationValue ?: 'San Francisco, CA') }}&output=embed"></iframe>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="bd-label">What to Bring</label>
                            <textarea name="what_to_bring" id="whatToBringTextarea" class="form-control bd-area @error('what_to_bring') is-invalid @enderror" rows="4" placeholder="Example: Stick, helmet, water bottle, training shoes">{{ $whatToBringValue }}</textarea>
                            @error('what_to_bring')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between gap-3 mb-2">
                                <label class="bd-label mb-0">Instructors <span class="required">*</span></label>
                                <button type="button" class="instructor-add-btn" id="addInstructorBtn">
                                    <i class="fa-solid fa-plus"></i> Add New
                                </button>
                            </div>

                            <input type="hidden" name="instructor" id="instructorCombinedInput" value="{{ old('instructor', $instructorValue) }}">

                            <div id="instructorFieldsWrapper" class="instructor-list">
                                @foreach($instructorList as $index => $instructorName)
                                    <div class="instructor-row">
                                        <div class="instructor-input-wrap">
                                            <span class="instructor-icon"><i class="fa-solid fa-user-tie"></i></span>
                                            <input type="text" name="instructors[]" class="form-control bd-input instructor-input @error('instructor') is-invalid @enderror @error('instructors.' . $index) is-invalid @enderror" value="{{ $instructorName }}" placeholder="Enter instructor name">
                                        </div>
                                        <button type="button" class="instructor-remove-btn remove-instructor-btn" title="Remove instructor">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            @error('instructor')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            @error('instructors')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="bd-label">Capacity <span class="required">*</span></label>
                            <input type="number" name="capacity" min="1" class="form-control bd-input @error('capacity') is-invalid @enderror" value="{{ $capacityValue }}" placeholder="Enter maximum number of players">
                            @error('capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="bd-label">Date <span class="required">*</span></label>
                            <input type="date" name="event_date" class="form-control bd-input @error('event_date') is-invalid @enderror" value="{{ old('event_date', optional($session)->event_date ? \Carbon\Carbon::parse($session->event_date)->format('Y-m-d') : '') }}">
                            @error('event_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="bd-label">Start Time <span class="required">*</span></label>
                            <input type="time" name="start_time" class="form-control bd-input @error('start_time') is-invalid @enderror" value="{{ old('start_time', optional($session)->start_time ? \Carbon\Carbon::parse($session->start_time)->format('H:i') : '') }}">
                            @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="bd-label">End Time <span class="required">*</span></label>
                            <input type="time" name="end_time" class="form-control bd-input @error('end_time') is-invalid @enderror" value="{{ old('end_time', optional($session)->end_time ? \Carbon\Carbon::parse($session->end_time)->format('H:i') : '') }}">
                            @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <div class="bd-info-box">
                                <div class="fw-bold mb-2">
                                    <i class="fa-solid fa-circle-info me-2"></i>Session Details
                                </div>
                                <div class="small">Training type, instructor, location, address, capacity, and what-to-bring information will be visible on customer-side class details.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bd-form-actions">
                <a href="{{ route('admin.em.sessions.index') }}" class="bd-btn bd-btn-outline">Cancel</a>
                <button type="submit" class="bd-btn bd-btn-primary">{{ $session ? 'Update Event' : 'Create Event' }}</button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var trainingCards = document.querySelectorAll('.training-card');
    var trainingInput = document.getElementById('trainingTypeInput');
    var sessionNameInput = document.getElementById('sessionNameInput');
    var descriptionTextarea = document.getElementById('descriptionTextarea');
    var whatToBringTextarea = document.getElementById('whatToBringTextarea');

    var defaultTrainingDescriptions = @json($defaultTrainingDescriptions);
    var defaultWhatToBring = @json($defaultWhatToBring);

    function setTrainingCardActive(selectedTraining) {
        trainingCards.forEach(function (item) {
            if (item.getAttribute('data-training') === selectedTraining) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    }

    trainingCards.forEach(function (card) {
        card.addEventListener('click', function () {
            var selectedTraining = card.getAttribute('data-training');

            if (!selectedTraining) {
                return;
            }

            if (trainingInput && trainingInput.value === selectedTraining) {
                setTrainingCardActive(selectedTraining);
                return;
            }

            setTrainingCardActive(selectedTraining);

            if (trainingInput) {
                trainingInput.value = selectedTraining;
            }

            if (sessionNameInput && sessionNameInput.value.trim() === '') {
                sessionNameInput.value = selectedTraining;
            }

            if (descriptionTextarea && Object.prototype.hasOwnProperty.call(defaultTrainingDescriptions, selectedTraining)) {
                descriptionTextarea.value = defaultTrainingDescriptions[selectedTraining];
                descriptionTextarea.dispatchEvent(new Event('input', { bubbles: true }));
            }

            if (whatToBringTextarea && Object.prototype.hasOwnProperty.call(defaultWhatToBring, selectedTraining)) {
                whatToBringTextarea.value = defaultWhatToBring[selectedTraining];
                whatToBringTextarea.dispatchEvent(new Event('input', { bubbles: true }));
            }
        });
    });

    var locationInput = document.getElementById('locationInput');
    var locationSuggestions = document.getElementById('locationSuggestions');
    var locationLat = document.getElementById('locationLat');
    var locationLng = document.getElementById('locationLng');
    var mapFrame = document.getElementById('mapFrame');
    var previewMapBtn = document.getElementById('previewMapBtn');
    var searchTimer = null;
    var searchRequestId = 0;

    function updateMap(query) {
        if (!mapFrame) {
            return;
        }

        var q = query || (locationInput ? locationInput.value : '') || 'San Francisco, CA';
        mapFrame.setAttribute('src', 'https://maps.google.com/maps?q=' + encodeURIComponent(q) + '&output=embed');
    }

    if (previewMapBtn) {
        previewMapBtn.addEventListener('click', function () {
            updateMap();
        });
    }

    if (locationInput) {
        locationInput.addEventListener('input', function () {
            var query = locationInput.value.trim();
            clearTimeout(searchTimer);

            if (locationLat) {
                locationLat.value = '';
            }

            if (locationLng) {
                locationLng.value = '';
            }

            if (!query || query.length < 3) {
                locationSuggestions.style.display = 'none';
                locationSuggestions.innerHTML = '';
                return;
            }

            var requestId = ++searchRequestId;

            searchTimer = setTimeout(function () {
                fetch('https://nominatim.openstreetmap.org/search?q=' + encodeURIComponent(query) + '&format=json&limit=6')
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Location search failed');
                        }

                        return response.json();
                    })
                    .then(function (rows) {
                        if (requestId !== searchRequestId) {
                            return;
                        }

                        var html = '';

                        rows.forEach(function (row) {
                            var safeName = String(row.display_name || '')
                                .replace(/&/g, '&amp;')
                                .replace(/</g, '&lt;')
                                .replace(/>/g, '&gt;')
                                .replace(/"/g, '&quot;')
                                .replace(/'/g, '&#039;');

                            html += '<div class="location-suggestion" data-name="' + safeName + '" data-lat="' + row.lat + '" data-lng="' + row.lon + '">' + safeName + '</div>';
                        });

                        locationSuggestions.innerHTML = html;
                        locationSuggestions.style.display = html ? 'block' : 'none';
                    })
                    .catch(function () {
                        if (requestId === searchRequestId) {
                            locationSuggestions.style.display = 'none';
                            locationSuggestions.innerHTML = '';
                        }
                    });
            }, 350);
        });
    }

    document.addEventListener('click', function (event) {
        var suggestion = event.target.closest('.location-suggestion');

        if (suggestion) {
            var name = suggestion.getAttribute('data-name');
            var lat = suggestion.getAttribute('data-lat');
            var lng = suggestion.getAttribute('data-lng');

            locationInput.value = name;
            locationLat.value = lat;
            locationLng.value = lng;
            locationSuggestions.style.display = 'none';
            locationSuggestions.innerHTML = '';
            updateMap(name);
            return;
        }

        if (!event.target.closest('#locationInput') && !event.target.closest('#locationSuggestions')) {
            if (locationSuggestions) {
                locationSuggestions.style.display = 'none';
            }
        }
    });

    var instructorWrapper = document.getElementById('instructorFieldsWrapper');
    var addInstructorBtn = document.getElementById('addInstructorBtn');
    var instructorCombinedInput = document.getElementById('instructorCombinedInput');

    function syncInstructorInput() {
        if (!instructorWrapper || !instructorCombinedInput) {
            return;
        }

        var instructors = [];

        instructorWrapper.querySelectorAll('.instructor-input').forEach(function (input) {
            var value = input.value.trim();

            if (value !== '') {
                instructors.push(value);
            }
        });

        instructorCombinedInput.value = instructors.join(', ');
    }

    if (addInstructorBtn && instructorWrapper) {
        addInstructorBtn.addEventListener('click', function () {
            var row = document.createElement('div');
            row.className = 'instructor-row';
            row.innerHTML = '<div class="instructor-input-wrap">'
                + '<span class="instructor-icon"><i class="fa-solid fa-user-tie"></i></span>'
                + '<input type="text" name="instructors[]" class="form-control bd-input instructor-input" placeholder="Enter instructor name">'
                + '</div>'
                + '<button type="button" class="instructor-remove-btn remove-instructor-btn" title="Remove instructor"><i class="fa-solid fa-trash"></i></button>';

            instructorWrapper.appendChild(row);
            row.querySelector('.instructor-input').focus();
            syncInstructorInput();
        });
    }

    document.addEventListener('click', function (event) {
        var removeBtn = event.target.closest('.remove-instructor-btn');

        if (!removeBtn || !instructorWrapper) {
            return;
        }

        var rows = instructorWrapper.querySelectorAll('.instructor-row');
        var row = removeBtn.closest('.instructor-row');

        if (rows.length > 1) {
            row.remove();
        } else {
            var input = row.querySelector('.instructor-input');

            if (input) {
                input.value = '';
            }
        }

        syncInstructorInput();
    });

    if (instructorWrapper) {
        instructorWrapper.addEventListener('input', function (event) {
            if (event.target.classList.contains('instructor-input')) {
                syncInstructorInput();
            }
        });
    }

    var form = document.querySelector('form.bd-form-card');

    if (form) {
        form.addEventListener('submit', function () {
            syncInstructorInput();
        });
    }

    syncInstructorInput();
});
</script>
@endsection
