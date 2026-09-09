@if(isset($manualCustomers) && isset($manualSessions))
<style>
    .ao-manual-dialog{max-width:760px}.ao-manual-content{border:0!important;border-radius:22px!important;overflow:hidden;background:#fff;box-shadow:0 28px 80px rgba(15,23,42,.28)}
    .ao-manual-head{padding:14px 22px 12px;background:linear-gradient(90deg,#fff,#fff6f6);border-bottom:1px solid #eee}.ao-manual-top{display:flex;align-items:center;justify-content:space-between;gap:12px}.ao-manual-kicker{display:flex;align-items:center;gap:8px;color:#f3282c;font-size:12px;font-weight:900;letter-spacing:.12em;text-transform:uppercase}.ao-manual-close{width:38px;height:38px;border:0;border-radius:50%;background:#111827;color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:17px}.ao-manual-close:hover{background:#f3282c}
    .ao-manual-steps{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:8px;margin-top:10px}.ao-manual-step{min-height:36px;padding:6px 10px;border-radius:999px;background:#f2f4f7;color:#7b8494;display:flex;align-items:center;gap:8px;font-size:10px;font-weight:900;letter-spacing:.06em;text-transform:uppercase}.ao-manual-step span{width:24px;height:24px;border-radius:50%;background:#e1e5ea;color:#111;display:inline-flex;align-items:center;justify-content:center;font-size:11px}.ao-manual-step.active{background:#111827;color:#fff}.ao-manual-step.active span{background:#f3282c;color:#fff}.ao-manual-step.done{background:#fff0f0;color:#f3282c}.ao-manual-step.done span{background:#f3282c;color:#fff}
    .ao-manual-body{min-height:430px;padding:20px 22px 24px;background:#fff}.ao-manual-pane{display:none}.ao-manual-pane.active{display:block}.ao-manual-field-label{margin-bottom:7px;color:#667085;font-size:10px;font-weight:900;letter-spacing:.09em;text-transform:uppercase}.ao-manual-select-wrap{position:relative}.ao-manual-select-icon{position:absolute;left:15px;top:50%;transform:translateY(-50%);z-index:2;color:#f3282c;font-size:14px;pointer-events:none}.ao-manual-select{min-height:48px;border:1px solid #d7dce3;border-radius:11px;padding-left:42px!important;font-size:13px;font-weight:800;box-shadow:none!important}.ao-manual-select:focus{border-color:#f3282c;box-shadow:0 0 0 3px rgba(243,40,44,.08)!important}.ao-credit-line{display:flex;align-items:center;gap:7px;margin-top:8px;color:#6f7783;font-size:10px;font-weight:800}.ao-credit-line i{color:#f3282c}.ao-credit-line strong{color:#f3282c;font-weight:900}
    .ao-choice-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:18px}.ao-choice{display:block;border:1px solid #dde2e8;border-radius:14px;padding:13px 14px;background:#fff;cursor:pointer;transition:.16s}.ao-choice.active{border-color:#f3282c;background:#fff7f7;box-shadow:0 0 0 3px rgba(243,40,44,.07)}.ao-choice input{accent-color:#f3282c;margin-right:7px}.ao-choice strong{font-size:12px}.ao-choice small{display:block;margin:4px 0 0 23px;color:#7b8494;font-size:10px;font-weight:700}
    .ao-new-player-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}.ao-manual-input{min-height:44px;border:1px solid #d7dce3;border-radius:11px;padding:0 12px;font-size:13px;font-weight:700;box-shadow:none!important}.ao-manual-input:focus{border-color:#f3282c;box-shadow:0 0 0 3px rgba(243,40,44,.08)!important}.ao-position-row{display:flex;gap:8px;flex-wrap:wrap}.ao-position-pill{border:1px solid #d7dce3;border-radius:999px;padding:7px 10px;background:#fff;font-size:11px;font-weight:800}.ao-position-pill input{accent-color:#f3282c}
    .ao-session-list{max-height:320px;overflow:auto;padding-right:3px}.ao-session-card{display:flex;justify-content:space-between;align-items:center;gap:12px;border:1px solid #e0e4ea;border-radius:12px;padding:12px 13px;margin-bottom:9px;cursor:pointer;background:#fff;transition:.15s}.ao-session-card:hover,.ao-session-card.selected{border-color:#f3282c;background:#fff7f7}.ao-session-card.disabled{opacity:.5;cursor:not-allowed}.ao-session-card strong{display:block;font-size:13px}.ao-session-meta{margin-top:3px;color:#7a8390;font-size:10px;font-weight:700}.ao-session-badge{flex:0 0 auto;border-radius:999px;padding:5px 8px;font-size:9px;font-weight:900}.ao-session-badge.available{background:#dcfce7;color:#15803d}.ao-session-badge.expired{background:#fff7ed;color:#c2410c}.ao-session-badge.full{background:#fee2e2;color:#b91c1c}
    .ao-confirm-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}.ao-confirm-box{border:1px solid #e0e4ea;border-radius:13px;padding:14px;background:#f9fafb}.ao-confirm-box.full{grid-column:1/-1}.ao-confirm-box span{display:block;color:#7b8494;font-size:9px;font-weight:900;letter-spacing:.09em;text-transform:uppercase}.ao-confirm-box strong{display:block;margin-top:6px;font-size:13px;color:#111827}.ao-manual-validation{margin-top:14px;border:1px solid #e0e4ea;border-radius:12px;padding:12px;background:#fff;color:#667085;font-size:11px;font-weight:700}
    .ao-manual-footer{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:15px 22px;border-top:1px solid #eceff3;background:#fff}.ao-manual-btn{min-height:40px;border-radius:999px;padding:0 17px;font-size:12px;font-weight:900;display:inline-flex;align-items:center;justify-content:center;gap:7px}.ao-manual-btn-light{border:1px solid #d1d7df;background:#fff;color:#111827}.ao-manual-btn-dark{border:0;background:#111827;color:#fff}.ao-manual-btn-red{border:0;background:#f3282c;color:#fff;box-shadow:0 8px 20px rgba(243,40,44,.22)}.ao-manual-btn-red:hover{background:#d91f23;color:#fff}.ao-manual-footer-right{display:flex;gap:8px}
    @media(max-width:700px){.ao-manual-dialog{margin:7px}.ao-manual-steps{grid-template-columns:1fr 1fr}.ao-manual-body{min-height:390px}.ao-new-player-grid,.ao-choice-grid,.ao-confirm-grid{grid-template-columns:1fr}.ao-confirm-box.full{grid-column:auto}.ao-manual-footer{align-items:stretch;flex-direction:column}.ao-manual-footer-right{display:grid;grid-template-columns:1fr 1fr}.ao-manual-btn{width:100%}}
</style>

<template id="aoManualBookingTemplate">
    <div class="modal-content ao-manual-content">
        <form method="POST" action="{{ route('admin.bookings.session-wise.manual.store') }}" id="aoManualBookingForm">
            @csrf
            <input type="hidden" name="allow_expired" id="aoManualAllowExpired" value="0">
            <input type="hidden" name="session_event_id" id="aoManualSessionId" value="">

            <div class="ao-manual-head">
                <div class="ao-manual-top">
                    <div class="ao-manual-kicker"><i class="fa-regular fa-calendar-plus"></i> Add Booking</div>
                    <button type="button" class="ao-manual-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="ao-manual-steps">
                    @foreach(['Customer','Player','Session','Confirm'] as $i => $label)
                        <div class="ao-manual-step {{ $i === 0 ? 'active' : '' }}" data-ao-step-indicator="{{ $i + 1 }}"><span>{{ $i + 1 }}</span>{{ $label }}</div>
                    @endforeach
                </div>
            </div>

            <div class="ao-manual-body">
                <section class="ao-manual-pane active" data-ao-pane="1">
                    <div class="ao-manual-field-label">Choose Parent / Customer</div>
                    <div class="ao-manual-select-wrap">
                        <i class="fa-solid fa-user ao-manual-select-icon"></i>
                        <select class="form-select ao-manual-select" name="customer_id" id="aoManualCustomer" required>
                            <option value="">Select customer</option>
                            @foreach($manualCustomers as $customerItem)
                                @php
                                    $customerItem = is_array($customerItem) ? $customerItem : (array) $customerItem;
                                    $cid = $customerItem['id'] ?? '';
                                    $credits = (int) ($customerItem['credits'] ?? $customerItem['available_credits'] ?? 0);
                                    $disabled = !empty($customerItem['disabled']) || !empty($customerItem['is_disabled']);
                                    $name = $customerItem['name'] ?? 'Customer';
                                    $email = $customerItem['email'] ?? '';
                                    $phone = $customerItem['phone'] ?? '';
                                @endphp
                                <option value="{{ $cid }}" data-credits="{{ $credits }}" @disabled($disabled)>{{ $name }}@if($email) — {{ $email }}@endif @if($phone) · {{ $phone }}@endif @if($disabled) — NO CREDITS @endif</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ao-credit-line"><i class="fa-solid fa-circle-info"></i><strong id="aoManualCreditCount">0</strong> Class Credits Available</div>
                </section>

                <section class="ao-manual-pane" data-ao-pane="2">
                    <div class="ao-manual-field-label">Booking For</div>
                    <div class="ao-choice-grid">
                        <label class="ao-choice active"><input type="radio" name="child_mode" value="existing" checked><strong>Existing Player</strong><small>Select from customer players.</small></label>
                        <label class="ao-choice"><input type="radio" name="child_mode" value="new"><strong>New Player</strong><small>Create and book a new player.</small></label>
                    </div>
                    <div id="aoExistingPlayerBox">
                        <div class="ao-manual-field-label">Select Player</div>
                        <div class="ao-manual-select-wrap"><i class="fa-solid fa-user ao-manual-select-icon"></i><select class="form-select ao-manual-select" name="child_id" id="aoManualChild"><option value="">Choose a player</option></select></div>
                    </div>
                    <div id="aoNewPlayerBox" class="d-none">
                        <div class="ao-new-player-grid">
                            <div><div class="ao-manual-field-label">First Name</div><input class="form-control ao-manual-input" name="player_first"></div>
                            <div><div class="ao-manual-field-label">Last Name</div><input class="form-control ao-manual-input" name="player_last"></div>
                            <div><div class="ao-manual-field-label">Grad Year</div><input class="form-control ao-manual-input" name="grad_year" inputmode="numeric"></div>
                            <div><div class="ao-manual-field-label">Positions</div><div class="ao-position-row">@foreach(['Attack','Middie','Defense','Goalie'] as $position)<label class="ao-position-pill"><input type="checkbox" name="positions[]" value="{{ $position }}"> {{ $position }}</label>@endforeach</div></div>
                        </div>
                    </div>
                </section>

                <section class="ao-manual-pane" data-ao-pane="3">
                    <div class="ao-manual-field-label">Choose Session</div>
                    <div class="ao-session-list">
                        @foreach($manualSessions as $manualSession)
                            @php
                                $manualSession = is_array($manualSession) ? $manualSession : (array) $manualSession;
                                $sid = $manualSession['id'] ?? '';
                                $title = $manualSession['title'] ?? $manualSession['name'] ?? $manualSession['training_type'] ?? 'Training Session';
                                $dateLabel = $manualSession['date_label'] ?? $manualSession['date'] ?? '';
                                $timeLabel = $manualSession['time'] ?? '';
                                $locationLabel = $manualSession['location'] ?? '';
                                $full = !empty($manualSession['full']) || !empty($manualSession['is_full']);
                                $expired = !empty($manualSession['expired']) || !empty($manualSession['is_expired']);
                                $left = $manualSession['left'] ?? $manualSession['left_count'] ?? null;
                            @endphp
                            <div class="ao-session-card {{ $full ? 'disabled' : '' }}" data-ao-session="{{ $sid }}" data-ao-full="{{ $full ? 1 : 0 }}" data-ao-expired="{{ $expired ? 1 : 0 }}">
                                <div><strong>{{ $title }}</strong><div class="ao-session-meta">{{ $dateLabel }} @if($timeLabel) · {{ $timeLabel }} @endif @if($locationLabel) · {{ $locationLabel }} @endif</div></div>
                                <span class="ao-session-badge {{ $full ? 'full' : ($expired ? 'expired' : 'available') }}">{{ $full ? 'Full' : ($expired ? 'Expired' : ($left === null ? 'Available' : $left . ' Left')) }}</span>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="ao-manual-pane" data-ao-pane="4">
                    <div class="ao-confirm-grid">
                        <div class="ao-confirm-box"><span>Customer</span><strong id="aoManualConfirmCustomer">—</strong></div>
                        <div class="ao-confirm-box"><span>Player</span><strong id="aoManualConfirmPlayer">—</strong></div>
                        <div class="ao-confirm-box full"><span>Session</span><strong id="aoManualConfirmSession">—</strong></div>
                    </div>
                    <div class="ao-manual-validation" id="aoManualValidation">The system will validate available credits, player ownership, duplicate bookings, capacity, and session availability before saving.</div>
                </section>
            </div>

            <div class="ao-manual-footer">
                <button type="button" class="ao-manual-btn ao-manual-btn-light" id="aoManualCancel" data-bs-dismiss="modal">Cancel</button>
                <div class="ao-manual-footer-right">
                    <button type="button" class="ao-manual-btn ao-manual-btn-light d-none" id="aoManualBack"><i class="fa-solid fa-arrow-left"></i> Previous</button>
                    <button type="button" class="ao-manual-btn ao-manual-btn-dark" id="aoManualNext">Next <i class="fa-solid fa-arrow-right"></i></button>
                    <button type="submit" class="ao-manual-btn ao-manual-btn-red d-none" id="aoManualSubmit"><i class="fa-solid fa-check"></i> Create Booking</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('addBookingModal');
    var template = document.getElementById('aoManualBookingTemplate');
    if (!modal || !template) return;

    var dialog = modal.querySelector('.modal-dialog');
    var oldContent = modal.querySelector('.modal-content');
    if (dialog) dialog.className = 'modal-dialog modal-dialog-centered ao-manual-dialog';
    if (oldContent) oldContent.replaceWith(template.content.firstElementChild.cloneNode(true));

    var childrenByCustomer = @json($childrenByCustomer ?? []);
    var form = document.getElementById('aoManualBookingForm');
    var customer = document.getElementById('aoManualCustomer');
    var child = document.getElementById('aoManualChild');
    var sessionInput = document.getElementById('aoManualSessionId');
    var allowExpired = document.getElementById('aoManualAllowExpired');
    var next = document.getElementById('aoManualNext');
    var back = document.getElementById('aoManualBack');
    var submit = document.getElementById('aoManualSubmit');
    var creditCount = document.getElementById('aoManualCreditCount');
    var validation = document.getElementById('aoManualValidation');
    var step = 1;

    function showStep(value) {
        step = value;
        document.querySelectorAll('[data-ao-pane]').forEach(function (pane) { pane.classList.toggle('active', Number(pane.dataset.aoPane) === step); });
        document.querySelectorAll('[data-ao-step-indicator]').forEach(function (item) {
            var itemStep = Number(item.dataset.aoStepIndicator);
            item.classList.toggle('active', itemStep === step);
            item.classList.toggle('done', itemStep < step);
        });
        back.classList.toggle('d-none', step === 1);
        next.classList.toggle('d-none', step === 4);
        submit.classList.toggle('d-none', step !== 4);
        if (step === 4) fillConfirm();
    }

    customer.addEventListener('change', function () {
        var option = customer.options[customer.selectedIndex];
        creditCount.textContent = option ? (option.dataset.credits || '0') : '0';
        child.innerHTML = '<option value="">Choose a player</option>';
        (childrenByCustomer[customer.value] || []).forEach(function (item) {
            var label = item.name || ((item.first_name || '') + ' ' + (item.last_name || '')).trim() || 'Player';
            if (item.meta) label += ' — ' + item.meta;
            child.add(new Option(label, item.id));
        });
    });

    form.querySelectorAll('input[name="child_mode"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            var newMode = form.querySelector('input[name="child_mode"]:checked').value === 'new';
            document.getElementById('aoExistingPlayerBox').classList.toggle('d-none', newMode);
            document.getElementById('aoNewPlayerBox').classList.toggle('d-none', !newMode);
            document.querySelectorAll('.ao-choice').forEach(function (choice) {
                var input = choice.querySelector('input[name="child_mode"]');
                choice.classList.toggle('active', !!input && input.checked);
            });
        });
    });

    document.querySelectorAll('.ao-session-card').forEach(function (card) {
        card.addEventListener('click', function () {
            if (card.dataset.aoFull === '1') return;
            document.querySelectorAll('.ao-session-card').forEach(function (item) { item.classList.remove('selected'); });
            card.classList.add('selected');
            sessionInput.value = card.dataset.aoSession || '';
            allowExpired.value = card.dataset.aoExpired === '1' ? '1' : '0';
        });
    });

    back.addEventListener('click', function () { showStep(Math.max(1, step - 1)); });
    next.addEventListener('click', function () {
        if (step === 1 && !customer.value) { window.alert('Select a customer.'); return; }
        if (step === 2) {
            var mode = form.querySelector('input[name="child_mode"]:checked').value;
            if (mode === 'existing' && !child.value) { window.alert('Select a player.'); return; }
            if (mode === 'new' && !String(form.querySelector('[name="player_first"]').value || '').trim()) { window.alert('Enter the player first name.'); return; }
        }
        if (step === 3 && !sessionInput.value) { window.alert('Select a session.'); return; }
        showStep(Math.min(4, step + 1));
    });

    function fillConfirm() {
        var customerText = customer.options[customer.selectedIndex] ? customer.options[customer.selectedIndex].text : '—';
        var mode = form.querySelector('input[name="child_mode"]:checked').value;
        var playerText = '—';
        if (mode === 'existing') {
            playerText = child.options[child.selectedIndex] ? child.options[child.selectedIndex].text : '—';
        } else {
            playerText = (String(form.querySelector('[name="player_first"]').value || '') + ' ' + String(form.querySelector('[name="player_last"]').value || '')).trim() || '—';
        }
        var sessionCard = document.querySelector('.ao-session-card.selected');
        document.getElementById('aoManualConfirmCustomer').textContent = customerText;
        document.getElementById('aoManualConfirmPlayer').textContent = playerText;
        document.getElementById('aoManualConfirmSession').textContent = sessionCard ? sessionCard.innerText.replace(/\s+/g, ' ').trim() : '—';
    }

    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        validation.className = 'ao-manual-validation';
        validation.textContent = 'Validating booking...';
        submit.disabled = true;
        try {
            var response = await fetch('{{ route('admin.bookings.session-wise.manual.validate') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            });
            var data = await response.json();
            if (!response.ok) {
                validation.style.borderColor = '#fecaca';
                validation.style.background = '#fef2f2';
                validation.style.color = '#b91c1c';
                validation.textContent = data.message || 'Booking validation failed.';
                submit.disabled = false;
                return;
            }
            validation.style.borderColor = '#bbf7d0';
            validation.style.background = '#f0fdf4';
            validation.style.color = '#15803d';
            validation.textContent = data.message || 'Booking is valid. Saving...';
            window.setTimeout(function () { HTMLFormElement.prototype.submit.call(form); }, 180);
        } catch (error) {
            validation.style.borderColor = '#fecaca';
            validation.style.background = '#fef2f2';
            validation.style.color = '#b91c1c';
            validation.textContent = 'Could not validate booking.';
            submit.disabled = false;
        }
    });

    modal.addEventListener('hidden.bs.modal', function () {
        form.reset();
        child.innerHTML = '<option value="">Choose a player</option>';
        sessionInput.value = '';
        allowExpired.value = '0';
        creditCount.textContent = '0';
        document.querySelectorAll('.ao-session-card').forEach(function (card) { card.classList.remove('selected'); });
        document.getElementById('aoExistingPlayerBox').classList.remove('d-none');
        document.getElementById('aoNewPlayerBox').classList.add('d-none');
        document.querySelectorAll('.ao-choice').forEach(function (choice, index) { choice.classList.toggle('active', index === 0); });
        validation.removeAttribute('style');
        validation.textContent = 'The system will validate available credits, player ownership, duplicate bookings, capacity, and session availability before saving.';
        submit.disabled = false;
        showStep(1);
    });

    showStep(1);
});
</script>
@endif
