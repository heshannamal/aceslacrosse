<div id="aoBaddiesBookingModal" class="ao-baddies-booking-backdrop" aria-hidden="true">
    <div class="ao-baddies-booking-modal" role="dialog" aria-modal="true" aria-labelledby="aoBaddiesBookingTitle">
        <div class="ao-baddies-booking-header">
            <div>
                <div class="ao-baddies-booking-kicker">BOOK SESSION</div>
                <h2 id="aoBaddiesBookingTitle">Training Session</h2>
                <p id="aoBaddiesBookingMeta"></p>
            </div>
            <button type="button" class="ao-baddies-booking-close" id="aoBaddiesBookingClose" aria-label="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="aoBaddiesBookingForm" novalidate>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div id="aoBaddiesBookingAlert" class="ao-baddies-booking-alert" hidden></div>

            <section class="ao-baddies-booking-page active" data-page="1">
                <div class="ao-baddies-booking-panel">
                    <div id="aoSavedPlayerBlock" class="ao-baddies-field-block">
                        <label for="aoExistingChild">CHOOSE EXISTING PLAYER</label>
                        <select id="aoExistingChild" name="child_id">
                            <option value="">Select Player</option>
                        </select>

                        <label class="ao-baddies-checkline">
                            <input type="checkbox" id="aoAddNewPlayer">
                            <span>Add new player</span>
                        </label>
                    </div>

                    <div id="aoNewPlayerFields" class="ao-baddies-new-player-fields">
                        <div class="ao-baddies-grid-2">
                            <div>
                                <label>PLAYER FIRST NAME <span>*</span></label>
                                <input type="text" id="aoPlayerFirst" name="player_first" autocomplete="given-name">
                            </div>
                            <div>
                                <label>PLAYER LAST NAME</label>
                                <input type="text" id="aoPlayerLast" name="player_last" autocomplete="family-name">
                            </div>
                        </div>

                        <div class="ao-baddies-field-block">
                            <label>GRAD YEAR</label>
                            <input type="number" id="aoGradYear" name="grad_year" min="2000" max="2100">
                        </div>

                        <div class="ao-baddies-field-block">
                            <label>POSITIONS</label>
                            <div class="ao-baddies-position-grid">
                                @foreach(['Attack', 'Middie', 'Defense', 'Goalie'] as $position)
                                    <label class="ao-baddies-position-option">
                                        <input type="checkbox" name="positions[]" value="{{ $position }}">
                                        <span>{{ $position }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ao-baddies-booking-actions">
                    <button type="button" class="ao-baddies-back-btn" id="aoBookingCancel">Cancel</button>
                    <button type="button" class="ao-baddies-primary-btn" id="aoBookingContinue">
                        Continue <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </section>

            <section class="ao-baddies-booking-page" data-page="2">
                <div class="ao-baddies-booking-panel payment-panel">
                    <div id="aoPaymentChoiceGrid" class="ao-baddies-payment-grid">
                        <label class="ao-baddies-choice-card" id="aoCreditChoiceCard">
                            <input type="radio" name="booking_payment_method" value="credit" id="aoCreditChoice">
                            <div>
                                <span>AVAILABLE CREDITS</span>
                                <strong id="aoAvailableCredits">0</strong>
                            </div>
                            <i class="ao-choice-dot"></i>
                        </label>

                        <label class="ao-baddies-choice-card" id="aoPackageChoiceCard">
                            <input type="radio" name="booking_payment_method" value="package" id="aoPackageChoice">
                            <div>
                                <span>ADD TO CART</span>
                                <strong class="buy-title">Buy New Credits</strong>
                            </div>
                            <i class="ao-choice-dot"></i>
                        </label>
                    </div>

                    <div id="aoNoCreditsMessage" class="ao-baddies-no-credits" hidden>
                        <strong>No credits available</strong>
                        <span>Select a package to buy Training credits.</span>
                    </div>

                    <div id="aoPackageCards" class="ao-baddies-package-grid"></div>
                </div>

                <div class="ao-baddies-booking-actions">
                    <button type="button" class="ao-baddies-back-btn" id="aoBookingBack">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </button>
                    <button type="submit" class="ao-baddies-primary-btn" id="aoBookingSubmit">
                        <span id="aoBookingSubmitLabel">Book Session</span>
                        <i id="aoBookingSubmitIcon" class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </section>
        </form>
    </div>
</div>

<style>
    .ao-baddies-booking-backdrop{position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,.68);backdrop-filter:blur(5px);display:none;align-items:center;justify-content:center;padding:18px;font-family:'Raleway','Karla',Arial,sans-serif}.ao-baddies-booking-backdrop.active{display:flex}.ao-baddies-booking-modal{width:min(820px,100%);max-height:92vh;overflow:auto;border-radius:14px;background:#fff;box-shadow:0 28px 85px rgba(0,0,0,.32);color:#050505;padding:14px}.ao-baddies-booking-header{position:sticky;top:0;z-index:3;padding:16px 14px 18px;background:#fff;border-bottom:1px solid #e6e6e6;display:flex;align-items:flex-start;justify-content:space-between;gap:20px}.ao-baddies-booking-kicker{color:#f3282c;font-size:10px;font-weight:900;letter-spacing:.22em}.ao-baddies-booking-header h2{margin:8px 0 2px;font-size:38px;line-height:1;font-weight:500;letter-spacing:-.035em}.ao-baddies-booking-header p{margin:0;color:#667085;font-size:13px;font-weight:800}.ao-baddies-booking-close{width:36px;height:36px;border:0;border-radius:50%;background:#050505;color:#fff;display:flex;align-items:center;justify-content:center;flex:0 0 auto}.ao-baddies-booking-alert{margin:12px 8px 0;padding:11px 13px;border:1px solid #fecaca;border-radius:11px;background:#fff1f2;color:#b42318;font-size:13px;font-weight:800}.ao-baddies-booking-page{display:none}.ao-baddies-booking-page.active{display:block}.ao-baddies-booking-panel{margin:8px;padding:13px;border:1px solid #e1e1e1;border-radius:20px;background:#fff}.ao-baddies-field-block{margin-bottom:18px}.ao-baddies-field-block>label,.ao-baddies-new-player-fields label{display:block;margin-bottom:8px;color:#667085;font-size:10px;font-weight:900;letter-spacing:.15em}.ao-baddies-new-player-fields label span{color:#f3282c}.ao-baddies-booking-panel select,.ao-baddies-booking-panel input[type=text],.ao-baddies-booking-panel input[type=number]{width:100%;min-height:44px;padding:9px 12px;border:1px solid #d7dce2;border-radius:11px;background:#fff;color:#111827;font-size:14px;font-weight:700;outline:none}.ao-baddies-booking-panel select:focus,.ao-baddies-booking-panel input[type=text]:focus,.ao-baddies-booking-panel input[type=number]:focus{border-color:#f3282c;box-shadow:0 0 0 3px rgba(243,40,44,.1)}.ao-baddies-checkline{display:inline-flex!important;align-items:center;gap:8px;margin:12px 0 0!important;color:#475467!important;font-size:13px!important;letter-spacing:0!important;cursor:pointer}.ao-baddies-checkline input,.ao-baddies-position-option input{accent-color:#f3282c}.ao-baddies-new-player-fields{display:none}.ao-baddies-new-player-fields.show{display:block}.ao-baddies-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px}.ao-baddies-position-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}.ao-baddies-position-option{margin:0!important;padding:11px;border:1px solid #dfe3e8;border-radius:11px;display:flex!important;align-items:center;gap:8px;color:#111827!important;font-size:13px!important;letter-spacing:0!important;cursor:pointer}.ao-baddies-position-option:hover{border-color:#f3282c}
    .ao-baddies-payment-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:18px}.ao-baddies-choice-card{position:relative;min-height:124px;padding:18px;border:2px solid #e2e5e9;border-radius:22px;background:#fff;display:flex;align-items:flex-start;justify-content:space-between;gap:14px;cursor:pointer;transition:.18s ease}.ao-baddies-choice-card input{position:absolute;opacity:0;pointer-events:none}.ao-baddies-choice-card.active{border-color:#f3282c;background:#fff7f7}.ao-baddies-choice-card span{display:block;color:#667085;font-size:10px;font-weight:900;letter-spacing:.15em}.ao-baddies-choice-card strong{display:block;margin-top:8px;color:#f3282c;font-family:'Archivo Black','Karla',sans-serif;font-size:37px;line-height:1}.ao-baddies-choice-card strong.buy-title{color:#111827;font-family:'Raleway','Karla',sans-serif;font-size:23px;font-weight:500;letter-spacing:-.025em}.ao-choice-dot{width:20px;height:20px;border:1.5px solid #c8cdd4;border-radius:50%;background:#fff;flex:0 0 auto;box-shadow:inset 0 0 0 4px #fff}.ao-baddies-choice-card.active .ao-choice-dot{border-color:#f3282c;background:#f3282c}.ao-baddies-no-credits{margin-bottom:16px;padding:14px;border:1px solid #fecaca;border-radius:14px;background:#fff7f7;display:flex;flex-direction:column;gap:3px}.ao-baddies-no-credits strong{font-size:17px}.ao-baddies-no-credits span{color:#667085;font-size:12px;font-weight:700}.ao-baddies-package-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}.ao-baddies-package-card{position:relative;min-height:140px;padding:15px;border:2px solid #e2e5e9;border-radius:18px;background:#fff;cursor:pointer;display:flex;flex-direction:column;justify-content:space-between;transition:.18s ease}.ao-baddies-package-card.active{border-color:#f3282c;background:#fff7f7}.ao-baddies-package-card input{position:absolute;opacity:0}.ao-baddies-package-top{display:flex;align-items:flex-start;justify-content:space-between;gap:10px}.ao-baddies-package-badge{display:inline-flex;padding:5px 9px;border-radius:999px;background:#050505;color:#fff;font-size:9px;font-weight:900;letter-spacing:.1em}.ao-baddies-package-card h4{margin:11px 0 0;font-size:17px;line-height:1.15;font-weight:500}.ao-baddies-package-card strong{color:#f3282c;font-size:24px;font-weight:500}.ao-baddies-package-card .ao-choice-dot{width:19px;height:19px}.ao-baddies-package-card.active .ao-choice-dot{border-color:#f3282c;background:#f3282c}
    .ao-baddies-booking-actions{padding:12px 8px 2px;display:flex;align-items:center;justify-content:space-between;gap:12px}.ao-baddies-back-btn,.ao-baddies-primary-btn{min-height:44px;border-radius:999px;padding:10px 16px;display:inline-flex;align-items:center;justify-content:center;gap:8px;font-size:13px;font-weight:900}.ao-baddies-back-btn{border:1.5px solid #050505;background:#fff;color:#050505}.ao-baddies-primary-btn{border:0;background:#f3282c;color:#fff;box-shadow:0 12px 24px rgba(243,40,44,.22)}.ao-baddies-primary-btn:disabled{opacity:.6;cursor:not-allowed}.ao-baddies-primary-btn:hover:not(:disabled){background:#d91f23}.ao-baddies-booking-modal.loading .ao-baddies-primary-btn{pointer-events:none;opacity:.68}
    @media(max-width:700px){.ao-baddies-booking-modal{padding:8px}.ao-baddies-booking-header h2{font-size:31px}.ao-baddies-grid-2,.ao-baddies-payment-grid,.ao-baddies-package-grid{grid-template-columns:1fr}.ao-baddies-position-grid{grid-template-columns:1fr 1fr}.ao-baddies-choice-card{min-height:108px}.ao-baddies-booking-actions{position:sticky;bottom:0;background:#fff;padding-bottom:8px}}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const overlay = document.getElementById('aoBaddiesBookingModal');
    const modal = overlay ? overlay.querySelector('.ao-baddies-booking-modal') : null;
    const form = document.getElementById('aoBaddiesBookingForm');
    const title = document.getElementById('aoBaddiesBookingTitle');
    const meta = document.getElementById('aoBaddiesBookingMeta');
    const alertBox = document.getElementById('aoBaddiesBookingAlert');
    const closeButton = document.getElementById('aoBaddiesBookingClose');
    const cancelButton = document.getElementById('aoBookingCancel');
    const continueButton = document.getElementById('aoBookingContinue');
    const backButton = document.getElementById('aoBookingBack');
    const submitButton = document.getElementById('aoBookingSubmit');
    const submitLabel = document.getElementById('aoBookingSubmitLabel');
    const submitIcon = document.getElementById('aoBookingSubmitIcon');
    const childSelect = document.getElementById('aoExistingChild');
    const savedPlayerBlock = document.getElementById('aoSavedPlayerBlock');
    const addNewPlayer = document.getElementById('aoAddNewPlayer');
    const newPlayerFields = document.getElementById('aoNewPlayerFields');
    const playerFirst = document.getElementById('aoPlayerFirst');
    const creditChoice = document.getElementById('aoCreditChoice');
    const packageChoice = document.getElementById('aoPackageChoice');
    const creditCard = document.getElementById('aoCreditChoiceCard');
    const packageCard = document.getElementById('aoPackageChoiceCard');
    const availableCredits = document.getElementById('aoAvailableCredits');
    const packageCards = document.getElementById('aoPackageCards');
    const noCreditsMessage = document.getElementById('aoNoCreditsMessage');

    if (!overlay || !modal || !form) return;

    let actionUrl = '';
    let sourceModal = null;
    let currentPage = 1;
    let creditsCount = 0;

    function setPage(page) {
        currentPage = page;
        overlay.querySelectorAll('.ao-baddies-booking-page').forEach(function (pane) {
            pane.classList.toggle('active', Number(pane.dataset.page) === page);
        });
        modal.scrollTop = 0;
    }

    function showAlert(message) {
        if (!message) {
            alertBox.hidden = true;
            alertBox.textContent = '';
            return;
        }
        alertBox.textContent = message;
        alertBox.hidden = false;
    }

    function closeModal() {
        overlay.classList.remove('active');
        overlay.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        showAlert('');
    }

    function openModal() {
        overlay.classList.add('active');
        overlay.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        setPage(1);
    }

    function syncNewPlayerFields() {
        const hasSaved = childSelect.options.length > 1;
        const useNew = !hasSaved || addNewPlayer.checked;
        savedPlayerBlock.style.display = hasSaved ? '' : 'none';
        newPlayerFields.classList.toggle('show', useNew);
        childSelect.disabled = useNew;
        playerFirst.required = useNew;
    }

    function parsePriceText(text) {
        const match = String(text || '').match(/\$\s*([0-9,.]+)/);
        return match ? '$' + match[1] : '';
    }

    function packageNameFromText(text) {
        return String(text || '').split('—')[0].trim();
    }

    function buildPackages(oldForm) {
        packageCards.innerHTML = '';
        const oldSelect = oldForm.querySelector('select[name="selected_package_id"]');
        const options = oldSelect ? Array.from(oldSelect.options).filter(function (option) { return option.value; }) : [];

        options.forEach(function (option, index) {
            const label = document.createElement('label');
            label.className = 'ao-baddies-package-card' + (index === 0 ? ' active' : '');
            label.innerHTML = ''
                + '<input type="radio" name="selected_package_id" value="' + option.value.replace(/"/g, '&quot;') + '" ' + (index === 0 ? 'checked' : '') + '>'
                + '<div class="ao-baddies-package-top"><div><span class="ao-baddies-package-badge">PACKAGE</span><h4></h4></div><i class="ao-choice-dot"></i></div>'
                + '<strong></strong>';
            label.querySelector('h4').textContent = packageNameFromText(option.textContent) || 'Training Package';
            label.querySelector('strong').textContent = parsePriceText(option.textContent) || '$0.00';
            packageCards.appendChild(label);
        });

        packageCards.querySelectorAll('.ao-baddies-package-card').forEach(function (card) {
            card.addEventListener('click', function () {
                packageCards.querySelectorAll('.ao-baddies-package-card').forEach(function (other) {
                    other.classList.remove('active');
                });
                card.classList.add('active');
            });
        });
    }

    function buildChildren(oldForm) {
        childSelect.innerHTML = '<option value="">Select Player</option>';
        const savedRadios = Array.from(oldForm.querySelectorAll('input[name="child_id"]')).filter(function (input) {
            return String(input.value || '').trim() !== '';
        });

        savedRadios.forEach(function (radio) {
            const label = radio.closest('label');
            const strong = label ? label.querySelector('strong') : null;
            const small = label ? label.querySelector('small') : null;
            const option = document.createElement('option');
            option.value = radio.value;
            option.textContent = (strong ? strong.textContent.trim() : 'Player') + (small && small.textContent.trim() ? ' · ' + small.textContent.trim() : '');
            childSelect.appendChild(option);
        });

        if (savedRadios.length) {
            childSelect.selectedIndex = 1;
            addNewPlayer.checked = false;
        } else {
            addNewPlayer.checked = true;
        }
        syncNewPlayerFields();
    }

    function readCredits(oldForm) {
        const creditRadio = oldForm.querySelector('input[name="booking_payment_method"][value="credit"]');
        creditsCount = 0;
        if (creditRadio) {
            const choice = creditRadio.closest('.training-payment-choice');
            const text = choice ? choice.textContent : '';
            const match = String(text).match(/(\d+)\s+credit/i);
            creditsCount = match ? Number(match[1]) : 1;
        }
        availableCredits.textContent = String(creditsCount);
        creditCard.style.display = creditsCount > 0 ? '' : 'none';
        noCreditsMessage.hidden = creditsCount > 0;

        if (creditsCount > 0) {
            creditChoice.checked = true;
            packageChoice.checked = false;
        } else {
            creditChoice.checked = false;
            packageChoice.checked = true;
        }
        syncPaymentChoice();
    }

    function syncPaymentChoice() {
        const usePackage = packageChoice.checked || creditsCount < 1;
        creditCard.classList.toggle('active', !usePackage && creditsCount > 0);
        packageCard.classList.toggle('active', usePackage);
        packageCards.style.display = usePackage ? 'grid' : 'none';
        submitLabel.textContent = usePackage ? 'Add To Cart' : 'Book Session';
        submitIcon.className = usePackage ? 'fa-solid fa-cart-shopping' : 'fa-solid fa-arrow-right';
    }

    function openFromTrainingButton(button) {
        const target = button.getAttribute('data-bs-target');
        if (!target || !target.startsWith('#trainingBook')) return false;

        sourceModal = document.querySelector(target);
        if (!sourceModal) return false;

        const oldForm = sourceModal.querySelector('form.training-booking-form');
        if (!oldForm) return false;

        actionUrl = oldForm.getAttribute('action') || '';
        if (!actionUrl) return false;

        const confirmCard = sourceModal.querySelector('.training-confirm-card');
        const oldTitle = confirmCard ? confirmCard.querySelector('h4') : null;
        const paragraphs = confirmCard ? confirmCard.querySelectorAll('p') : [];
        title.textContent = oldTitle ? oldTitle.textContent.trim() : 'Training Session';
        meta.textContent = paragraphs.length ? paragraphs[0].textContent.trim() : '';

        buildChildren(oldForm);
        buildPackages(oldForm);
        readCredits(oldForm);

        playerFirst.value = '';
        document.getElementById('aoPlayerLast').value = '';
        document.getElementById('aoGradYear').value = '';
        form.querySelectorAll('input[name="positions[]"]').forEach(function (input) { input.checked = false; });
        showAlert('');
        openModal();
        return true;
    }

    document.addEventListener('click', function (event) {
        const button = event.target.closest('.training-red-button[data-bs-target^="#trainingBook"]');
        if (!button) return;
        if (openFromTrainingButton(button)) {
            event.preventDefault();
            event.stopPropagation();
        }
    }, true);

    addNewPlayer.addEventListener('change', syncNewPlayerFields);
    creditChoice.addEventListener('change', syncPaymentChoice);
    packageChoice.addEventListener('change', syncPaymentChoice);
    creditCard.addEventListener('click', function () { if (creditsCount > 0) { creditChoice.checked = true; syncPaymentChoice(); } });
    packageCard.addEventListener('click', function () { packageChoice.checked = true; syncPaymentChoice(); });

    continueButton.addEventListener('click', function () {
        showAlert('');
        const useNew = addNewPlayer.checked || childSelect.options.length <= 1;
        if (!useNew && !childSelect.value) {
            showAlert('Please select a player or choose Add new player.');
            return;
        }
        if (useNew && !playerFirst.value.trim()) {
            showAlert('Please enter the new player first name.');
            playerFirst.focus();
            return;
        }
        setPage(2);
    });

    backButton.addEventListener('click', function () { setPage(1); });
    cancelButton.addEventListener('click', closeModal);
    closeButton.addEventListener('click', closeModal);
    overlay.addEventListener('click', function (event) { if (event.target === overlay) closeModal(); });
    document.addEventListener('keydown', function (event) { if (event.key === 'Escape' && overlay.classList.contains('active')) closeModal(); });

    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        showAlert('');
        if (!actionUrl) return;

        const data = new FormData();
        data.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        const useNew = addNewPlayer.checked || childSelect.options.length <= 1;
        if (!useNew) {
            data.append('child_id', childSelect.value);
        } else {
            data.append('player_first', playerFirst.value.trim());
            data.append('player_last', document.getElementById('aoPlayerLast').value.trim());
            data.append('grad_year', document.getElementById('aoGradYear').value.trim());
            form.querySelectorAll('input[name="positions[]"]:checked').forEach(function (input) {
                data.append('positions[]', input.value);
            });
        }

        const method = packageChoice.checked || creditsCount < 1 ? 'package' : 'credit';
        data.append('booking_payment_method', method);

        if (method === 'package') {
            const selectedPackage = packageCards.querySelector('input[name="selected_package_id"]:checked');
            if (!selectedPackage) {
                showAlert('Please select a Training package.');
                return;
            }
            data.append('selected_package_id', selectedPackage.value);
        }

        submitButton.disabled = true;
        modal.classList.add('loading');

        try {
            const response = await fetch(actionUrl, {
                method: 'POST',
                body: data,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            let payload = {};
            try { payload = await response.json(); } catch (e) { payload = {}; }

            if (!response.ok || !payload.status) {
                const message = payload.message || (payload.errors ? Object.values(payload.errors).flat()[0] : null) || 'Booking failed. Please try again.';
                showAlert(message);
                return;
            }

            if (payload.redirect) {
                window.location.href = payload.redirect;
                return;
            }

            window.location.reload();
        } catch (error) {
            showAlert('Unable to complete the booking. Please try again.');
        } finally {
            submitButton.disabled = false;
            modal.classList.remove('loading');
        }
    });
});
</script>
