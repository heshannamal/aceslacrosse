<style>
    /* ACES Training admin theme overrides */
    :root {
        --aces-training-accent: #611eb2;
        --aces-training-accent-dark: #4d168f;
        --aces-training-accent-soft: #f4edfc;
    }

    @if(request()->routeIs('admin.em.sessions.create') || request()->routeIs('admin.em.sessions.edit'))
        :root {
            --ao-primary: #611eb2 !important;
            --ao-primary-dark: #4d168f !important;
            --ao-primary-soft: #f4edfc !important;
            --ao-bg: #f5f3f7 !important;
            --ao-border: #ddd6e8 !important;
        }

        .bd-form-head {
            background: linear-gradient(90deg, #fff, #faf7ff 65%, #f1e6fc) !important;
        }
        .training-card:hover {
            box-shadow: 0 10px 24px rgba(97, 30, 178, .10) !important;
        }
        .training-card.active {
            box-shadow: 0 0 0 4px rgba(97, 30, 178, .10) !important;
        }
        .bd-input:focus,
        .bd-area:focus {
            box-shadow: 0 0 0 4px rgba(97, 30, 178, .12) !important;
        }
        .instructor-add-btn {
            box-shadow: 0 10px 22px rgba(97, 30, 178, .18) !important;
        }
        .bd-info-box {
            background: #faf7ff !important;
            border-color: #ddc9f2 !important;
        }
        .input-group-text .fa-location-dot,
        .bd-form-page .fa-location-dot {
            color: #611eb2 !important;
        }
        .bd-form-page .btn-outline-danger {
            color: #611eb2 !important;
            border-color: #611eb2 !important;
        }
        .bd-form-page .btn-outline-danger:hover {
            color: #fff !important;
            background: #611eb2 !important;
        }
    @endif

    @if(request()->routeIs('admin.parents.booking.index') || request()->routeIs('admin.bookings.session-wise') || request()->routeIs('admin.payments.index'))
        .member-table,
        .booking-table,
        .pay-table {
            font-size: 12px !important;
            margin-bottom: 0 !important;
        }

        .member-table > :not(caption) > * > *,
        .booking-table > :not(caption) > * > *,
        .pay-table > :not(caption) > * > * {
            padding: .27rem .42rem !important;
            line-height: 1.18 !important;
            vertical-align: middle !important;
        }

        .member-table thead th,
        .booking-table thead th,
        .pay-table thead th {
            font-size: 11px !important;
            font-weight: 800 !important;
            text-transform: none !important;
            background: #f8fafc !important;
            color: #111827 !important;
            white-space: nowrap !important;
        }

        .member-table tbody tr,
        .booking-table tbody tr,
        .pay-table tbody tr {
            height: 32px !important;
        }

        .member-table td,
        .booking-table td,
        .pay-table td {
            font-size: 12px !important;
        }

        .member-table strong,
        .booking-table strong,
        .pay-table strong {
            font-weight: 600 !important;
        }

        .member-action,
        .booking-action-area .action-icon-btn {
            width: 30px !important;
            height: 30px !important;
            min-width: 30px !important;
            border-radius: 8px !important;
            padding: 0 !important;
        }
    @endif

    @if(request()->routeIs('admin.parents.booking.index'))
        /* The Baddies reference table keeps each member on one compact line. */
        .member-table td:first-child .small-muted,
        .member-table td:nth-child(5) .small-muted,
        .member-table td:nth-child(6) .small-muted {
            display: none !important;
        }
        .member-table td:nth-child(2) .small-muted {
            display: inline !important;
            margin-left: 4px !important;
            font-size: 10px !important;
        }
        .member-filter {
            padding: 12px 14px !important;
            margin-bottom: 12px !important;
        }
        .member-panel {
            padding: 14px !important;
        }
        .member-action.btn-outline-primary {
            color: #611eb2 !important;
            border-color: #611eb2 !important;
            background: #fff !important;
        }
        .member-action.btn-outline-primary:hover {
            color: #fff !important;
            border-color: #611eb2 !important;
            background: #611eb2 !important;
        }
    @endif

    @if(request()->routeIs('admin.bookings.session-wise'))
        .booking-page {
            padding: 12px !important;
        }
        .booking-page .filter-card {
            padding: 14px 16px !important;
            margin-bottom: 14px !important;
        }
        .booking-page .table-card {
            padding: 12px !important;
        }
        .booking-page .action-icon-btn.edit {
            color: #611eb2 !important;
            background: #f4edfc !important;
            border-color: #d7baf1 !important;
        }
        .booking-page .action-icon-btn.edit:hover {
            background: #611eb2 !important;
            color: #fff !important;
            border-color: #611eb2 !important;
        }
        .booking-page .add-booking-btn {
            background: #611eb2 !important;
            color: #fff !important;
            box-shadow: 0 10px 24px rgba(97, 30, 178, .22) !important;
        }
        .booking-page .booking-count-badge {
            background: #f4edfc !important;
            color: #611eb2 !important;
        }
        .booking-page .filter-input:focus,
        .booking-page .filter-select:focus {
            border-color: #611eb2 !important;
            box-shadow: 0 0 0 3px rgba(97, 30, 178, .12) !important;
        }
    @endif

    @if(request()->routeIs('admin.payments.index'))
        .pay-filter {
            padding: 14px 16px !important;
            margin-bottom: 14px !important;
        }
        .pay-table-card {
            padding: 12px !important;
        }
        .pay-filter .btn-primary {
            background: #611eb2 !important;
            border-color: #611eb2 !important;
        }
        .pay-filter .btn-primary:hover {
            background: #4d168f !important;
            border-color: #4d168f !important;
        }
        .payment-method-badge {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            padding: 3px 8px !important;
            border-radius: 999px !important;
            background: #f4edfc !important;
            color: #611eb2 !important;
            font-size: 11px !important;
            line-height: 1.1 !important;
            font-weight: 800 !important;
            text-transform: capitalize !important;
        }
    @endif
</style>

@if(request()->routeIs('admin.em.sessions.create') || request()->routeIs('admin.em.sessions.edit'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    var description = document.querySelector('textarea[name="description"]');
    var trainingType = document.getElementById('trainingTypeInput');

    var acesDescriptions = {
        Stickwork: 'ACES Lacrosse stickwork training builds confident catching, throwing, ball control and stick mechanics through focused repetition. Players work from proper grip and stance into body mechanics, form, accuracy and higher-level skills that transfer directly to game situations.',
        Speedwork: 'ACES Lacrosse speedwork develops acceleration, agility, movement mechanics, body control and coordination. Training focuses on how athletes move as well as how fast they move, creating a stronger athletic foundation for every part of the game.',
        Fieldwork: 'ACES Lacrosse fieldwork brings stickwork and speedwork together in live lacrosse situations. Players apply technical skills to positioning, leverage, spacing, reads and decision-making at game speed on both offense and defense.'
    };

    function shouldReplaceDescription(value) {
        var text = (value || '').trim();
        return text === '' || /alcatraz/i.test(text);
    }

    function applyAcesDescription() {
        if (!description || !trainingType) return;
        var type = trainingType.value || 'Stickwork';
        if (acesDescriptions[type] && shouldReplaceDescription(description.value)) {
            description.value = acesDescriptions[type];
            description.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    applyAcesDescription();

    document.querySelectorAll('.training-card').forEach(function (card) {
        card.addEventListener('click', function () {
            window.setTimeout(function () {
                var type = trainingType ? trainingType.value : card.getAttribute('data-training');
                if (description && type && acesDescriptions[type]) {
                    description.value = acesDescriptions[type];
                    description.dispatchEvent(new Event('input', { bubbles: true }));
                }
            }, 0);
        });
    });
});
</script>
@endif

@if(request()->routeIs('admin.payments.index'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.pay-table tbody .payment-row').forEach(function (row) {
        var methodCell = row.children[5];
        if (!methodCell || methodCell.querySelector('.payment-method-badge')) {
            return;
        }

        var method = (methodCell.textContent || '').trim();
        if (!method || method === '—' || method === '-') {
            return;
        }

        methodCell.textContent = '';
        var badge = document.createElement('span');
        badge.className = 'payment-method-badge';
        badge.textContent = method.toLowerCase();
        methodCell.appendChild(badge);
    });
});
</script>
@endif
