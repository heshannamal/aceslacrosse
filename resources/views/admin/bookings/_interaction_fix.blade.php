@if(isset($bookings))
@foreach($bookings as $booking)
    @php
        $currentSession = $booking->sessionEvent;
        $currentSessionId = (int) ($booking->session_event_id ?? 0);
        $currentSessionTitle = $currentSession
            ? ($currentSession->training_type ?: ($currentSession->name ?: 'Training Session'))
            : 'Training Session';
        $currentSessionDate = $currentSession && $currentSession->event_date
            ? \Carbon\Carbon::parse($currentSession->event_date)->format('M d, Y')
            : '-';
        $currentSessionStart = $currentSession && $currentSession->start_time
            ? \Carbon\Carbon::parse($currentSession->start_time)->format('g:i A')
            : '';
        $currentSessionEnd = $currentSession && $currentSession->end_time
            ? \Carbon\Carbon::parse($currentSession->end_time)->format('g:i A')
            : '';
        $currentSessionTime = trim($currentSessionStart . ($currentSessionEnd !== '' ? ' - ' . $currentSessionEnd : ''));
        $currentSessionLabel = $currentSessionTitle . ' — ' . $currentSessionDate . ($currentSessionTime !== '' ? ' — ' . $currentSessionTime : '');
    @endphp
    <template
        id="aoBookingEditMeta{{ $booking->id }}"
        data-current-session-id="{{ $currentSessionId }}"
        data-current-session-label="{{ $currentSessionLabel }}">
    </template>
@endforeach

<style>
    .booking-held-date-picker {
        position: relative;
        width: 100%;
    }

    .booking-held-date-trigger {
        width: 100%;
        min-height: 44px;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        background: #fff;
        color: #111827;
        padding: 0 40px 0 14px;
        display: flex;
        align-items: center;
        text-align: left;
        font-weight: 700;
        cursor: pointer;
        position: relative;
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    .booking-held-date-trigger:hover {
        border-color: #b8bfca;
    }

    .booking-held-date-picker.open .booking-held-date-trigger {
        border-color: #611eb2;
        box-shadow: 0 0 0 3px rgba(97, 30, 178, .12);
    }

    .booking-held-date-placeholder {
        color: #6b7280;
        font-weight: 700;
    }

    .booking-held-date-clear {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 24px;
        height: 24px;
        border: 0;
        background: transparent;
        color: #c9ced7;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 0;
        font-size: 18px;
        line-height: 1;
        cursor: pointer;
        z-index: 2;
    }

    .booking-held-date-picker.has-value .booking-held-date-clear {
        display: inline-flex;
    }

    .booking-held-date-clear:hover {
        color: #611eb2;
    }

    .booking-held-date-calendar {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        width: 308px;
        max-width: calc(100vw - 32px);
        background: #fff;
        border: 1px solid #e1e5eb;
        border-radius: 15px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, .16);
        padding: 8px 10px 12px;
        z-index: 10550;
        display: none;
    }

    .booking-held-date-picker.open .booking-held-date-calendar {
        display: block;
    }

    .booking-calendar-header {
        display: grid;
        grid-template-columns: 30px 1fr 30px;
        align-items: center;
        gap: 4px;
        padding: 0 0 6px;
    }

    .booking-calendar-nav {
        width: 30px;
        height: 30px;
        border: 0;
        border-radius: 999px;
        background: transparent;
        color: #4b5563;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 16px;
    }

    .booking-calendar-nav:hover {
        background: #f4f0fa;
        color: #611eb2;
    }

    .booking-calendar-title {
        text-align: center;
        color: #262626;
        font-size: 17px;
        font-weight: 500;
        line-height: 1.2;
        user-select: none;
    }

    .booking-calendar-month::after {
        content: '';
        display: inline-block;
        width: 0;
        height: 0;
        margin-left: 4px;
        vertical-align: 3px;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid #444;
    }

    .booking-calendar-weekdays,
    .booking-calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
    }

    .booking-calendar-weekdays {
        margin-bottom: 2px;
    }

    .booking-calendar-weekday {
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #5c6470;
        font-size: 12px;
        font-weight: 600;
    }

    .booking-calendar-day {
        width: 36px;
        height: 36px;
        margin: 1px auto;
        border: 0;
        border-radius: 50%;
        background: transparent;
        color: #34383f;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        line-height: 1;
        cursor: pointer;
        position: relative;
    }

    .booking-calendar-day:hover:not(:disabled) {
        background: #f4f0fa;
        color: #611eb2;
    }

    .booking-calendar-day.outside-month,
    .booking-calendar-day:disabled {
        color: #d9dde4;
        cursor: default;
        background: transparent;
    }

    .booking-calendar-day.selected {
        color: #111827;
        background: #fff;
        box-shadow: inset 0 0 0 2px #611eb2;
    }

    .booking-calendar-day.selected:hover {
        background: #f8f4fd;
    }

    @media (max-width: 767px) {
        .booking-held-date-calendar {
            width: min(308px, calc(100vw - 42px));
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function shortReference(value) {
        var text = (value || '').toString().trim();
        if (!text) {
            return text;
        }

        var parts = text.split('-').filter(Boolean);
        return parts.length > 1 ? parts[parts.length - 1] : text;
    }

    function normalizeDateValue(value) {
        var text = (value || '').toString().trim();
        if (!/^\d{4}-\d{2}-\d{2}$/.test(text)) {
            return '';
        }
        return text;
    }

    function dateFromValue(value) {
        var normalized = normalizeDateValue(value);
        if (!normalized) {
            return null;
        }

        var parts = normalized.split('-');
        return new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
    }

    function valueFromDate(date) {
        var year = date.getFullYear();
        var month = String(date.getMonth() + 1).padStart(2, '0');
        var day = String(date.getDate()).padStart(2, '0');
        return year + '-' + month + '-' + day;
    }

    function displayDate(value) {
        var date = dateFromValue(value);
        if (!date) {
            return 'Select held date';
        }

        var month = String(date.getMonth() + 1).padStart(2, '0');
        var day = String(date.getDate()).padStart(2, '0');
        return month + '/' + day + '/' + date.getFullYear();
    }

    function initBaddiesCalendar() {
        var heldDateSelect = document.querySelector('.booking-page form.filter-grid select[name="session_date"]');
        if (!heldDateSelect) {
            return;
        }

        var availableDates = Array.prototype.slice.call(heldDateSelect.options)
            .map(function (option) { return normalizeDateValue(option.value); })
            .filter(Boolean);
        var availableDateSet = new Set(availableDates);
        var initialValue = normalizeDateValue(heldDateSelect.value);

        var wrapper = document.createElement('div');
        wrapper.className = 'booking-held-date-picker' + (initialValue ? ' has-value' : '');

        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'session_date';
        hiddenInput.value = initialValue;

        var trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'booking-held-date-trigger';
        trigger.setAttribute('aria-haspopup', 'dialog');
        trigger.setAttribute('aria-expanded', 'false');

        var triggerText = document.createElement('span');
        triggerText.className = initialValue ? '' : 'booking-held-date-placeholder';
        triggerText.textContent = displayDate(initialValue);
        trigger.appendChild(triggerText);

        var clearButton = document.createElement('button');
        clearButton.type = 'button';
        clearButton.className = 'booking-held-date-clear';
        clearButton.setAttribute('aria-label', 'Clear held date');
        clearButton.title = 'Clear date';
        clearButton.innerHTML = '&times;';

        var calendar = document.createElement('div');
        calendar.className = 'booking-held-date-calendar';
        calendar.setAttribute('role', 'dialog');
        calendar.setAttribute('aria-label', 'Choose session held date');

        calendar.innerHTML = '' +
            '<div class="booking-calendar-header">' +
                '<button type="button" class="booking-calendar-nav booking-calendar-prev" aria-label="Previous month"><i class="fa-solid fa-chevron-left"></i></button>' +
                '<div class="booking-calendar-title"><span class="booking-calendar-month"></span> <span class="booking-calendar-year"></span></div>' +
                '<button type="button" class="booking-calendar-nav booking-calendar-next" aria-label="Next month"><i class="fa-solid fa-chevron-right"></i></button>' +
            '</div>' +
            '<div class="booking-calendar-weekdays">' +
                '<span class="booking-calendar-weekday">Sun</span>' +
                '<span class="booking-calendar-weekday">Mon</span>' +
                '<span class="booking-calendar-weekday">Tue</span>' +
                '<span class="booking-calendar-weekday">Wed</span>' +
                '<span class="booking-calendar-weekday">Thu</span>' +
                '<span class="booking-calendar-weekday">Fri</span>' +
                '<span class="booking-calendar-weekday">Sat</span>' +
            '</div>' +
            '<div class="booking-calendar-days"></div>';

        wrapper.appendChild(hiddenInput);
        wrapper.appendChild(trigger);
        wrapper.appendChild(clearButton);
        wrapper.appendChild(calendar);
        heldDateSelect.replaceWith(wrapper);

        var monthLabel = calendar.querySelector('.booking-calendar-month');
        var yearLabel = calendar.querySelector('.booking-calendar-year');
        var daysGrid = calendar.querySelector('.booking-calendar-days');
        var prevButton = calendar.querySelector('.booking-calendar-prev');
        var nextButton = calendar.querySelector('.booking-calendar-next');
        var monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        var selectedDate = dateFromValue(initialValue);
        var today = new Date();
        var firstAvailableDate = availableDates.length ? dateFromValue(availableDates[0]) : null;
        var visibleDate = selectedDate || today;

        if (!selectedDate && firstAvailableDate && availableDates.every(function (value) {
            var date = dateFromValue(value);
            return date && (date.getFullYear() !== today.getFullYear() || date.getMonth() !== today.getMonth());
        })) {
            visibleDate = firstAvailableDate;
        }

        visibleDate = new Date(visibleDate.getFullYear(), visibleDate.getMonth(), 1);

        function syncTrigger() {
            var value = normalizeDateValue(hiddenInput.value);
            triggerText.textContent = displayDate(value);
            triggerText.classList.toggle('booking-held-date-placeholder', !value);
            wrapper.classList.toggle('has-value', !!value);
        }

        function closeCalendar() {
            wrapper.classList.remove('open');
            trigger.setAttribute('aria-expanded', 'false');
        }

        function openCalendar() {
            document.querySelectorAll('.booking-held-date-picker.open').forEach(function (picker) {
                if (picker !== wrapper) {
                    picker.classList.remove('open');
                    var otherTrigger = picker.querySelector('.booking-held-date-trigger');
                    if (otherTrigger) {
                        otherTrigger.setAttribute('aria-expanded', 'false');
                    }
                }
            });

            var current = dateFromValue(hiddenInput.value);
            if (current) {
                visibleDate = new Date(current.getFullYear(), current.getMonth(), 1);
            }

            renderCalendar();
            wrapper.classList.add('open');
            trigger.setAttribute('aria-expanded', 'true');
        }

        function makeDayButton(date, outsideMonth) {
            var value = valueFromDate(date);
            var button = document.createElement('button');
            button.type = 'button';
            button.className = 'booking-calendar-day';
            button.textContent = date.getDate();
            button.setAttribute('data-date', value);

            if (outsideMonth) {
                button.classList.add('outside-month');
            }

            var isAvailable = availableDateSet.size === 0 || availableDateSet.has(value);
            if (outsideMonth || !isAvailable) {
                button.disabled = true;
            }

            if (normalizeDateValue(hiddenInput.value) === value) {
                button.classList.add('selected');
                button.setAttribute('aria-selected', 'true');
            }

            if (!button.disabled) {
                button.addEventListener('click', function () {
                    hiddenInput.value = value;
                    syncTrigger();
                    closeCalendar();
                });
            }

            return button;
        }

        function renderCalendar() {
            var year = visibleDate.getFullYear();
            var month = visibleDate.getMonth();
            monthLabel.textContent = monthNames[month];
            yearLabel.textContent = year;
            daysGrid.innerHTML = '';

            var firstDay = new Date(year, month, 1);
            var leadingDays = firstDay.getDay();
            var daysInMonth = new Date(year, month + 1, 0).getDate();
            var previousMonthDays = new Date(year, month, 0).getDate();

            for (var lead = leadingDays - 1; lead >= 0; lead--) {
                var previousDate = new Date(year, month - 1, previousMonthDays - lead);
                daysGrid.appendChild(makeDayButton(previousDate, true));
            }

            for (var day = 1; day <= daysInMonth; day++) {
                daysGrid.appendChild(makeDayButton(new Date(year, month, day), false));
            }

            var renderedCount = leadingDays + daysInMonth;
            var trailingDays = (7 - (renderedCount % 7)) % 7;
            if (renderedCount + trailingDays < 42) {
                trailingDays += 7;
            }

            for (var trailing = 1; trailing <= trailingDays; trailing++) {
                daysGrid.appendChild(makeDayButton(new Date(year, month + 1, trailing), true));
            }
        }

        trigger.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            if (wrapper.classList.contains('open')) {
                closeCalendar();
            } else {
                openCalendar();
            }
        });

        clearButton.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            hiddenInput.value = '';
            syncTrigger();
            renderCalendar();
            closeCalendar();
        });

        prevButton.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            visibleDate = new Date(visibleDate.getFullYear(), visibleDate.getMonth() - 1, 1);
            renderCalendar();
        });

        nextButton.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            visibleDate = new Date(visibleDate.getFullYear(), visibleDate.getMonth() + 1, 1);
            renderCalendar();
        });

        calendar.addEventListener('click', function (event) {
            event.stopPropagation();
        });

        document.addEventListener('click', function (event) {
            if (!wrapper.contains(event.target)) {
                closeCalendar();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && wrapper.classList.contains('open')) {
                closeCalendar();
                trigger.focus();
            }
        });

        renderCalendar();
        syncTrigger();
    }

    initBaddiesCalendar();

    // Show only the final token of generated booking references, e.g.
    // BKG-20260909-XQAPFO -> XQAPFO. Keep the full reference in the title.
    document.querySelectorAll('.booking-table tbody tr').forEach(function (row) {
        var firstCell = row.children[0];
        if (!firstCell) {
            return;
        }

        var strong = firstCell.querySelector('strong');
        if (!strong) {
            return;
        }

        var fullCode = strong.dataset.fullReference || strong.textContent.trim();
        if (!fullCode) {
            return;
        }

        strong.title = fullCode;
        strong.dataset.fullReference = fullCode;
        strong.textContent = shortReference(fullCode);
    });

    // Keep the booking detail modal consistent with the shortened table display.
    document.querySelectorAll('[id^="bookingDetail"] .modal-header h5').forEach(function (heading) {
        var fullCode = heading.textContent.trim();
        if (!fullCode) {
            return;
        }

        heading.title = fullCode;
        heading.textContent = shortReference(fullCode);
    });

    // Run after the Baddies-style modal replacement callback registered above this partial.
    window.requestAnimationFrame(function () {
        // The table row should open details only when a non-interactive area is clicked.
        // Edit/Delete/Cancel controls must never fall through to the row details modal.
        document.querySelectorAll('tr[data-bs-target^="#bookingDetail"]').forEach(function (row) {
            var detailTarget = row.getAttribute('data-bs-target');
            if (!detailTarget) {
                return;
            }

            row.removeAttribute('data-bs-toggle');
            row.removeAttribute('data-bs-target');
            row.dataset.aoDetailTarget = detailTarget;

            row.addEventListener('click', function (event) {
                if (event.target.closest('button, a, input, select, textarea, label, form, [data-bs-toggle], [data-bs-dismiss]')) {
                    return;
                }

                var targetModal = document.querySelector(detailTarget);
                if (!targetModal || !window.bootstrap) {
                    return;
                }

                bootstrap.Modal.getOrCreateInstance(targetModal).show();
            });
        });

        // Extra protection for action cells/forms, including cancelling a browser confirm.
        document.querySelectorAll('.booking-table tbody td:last-child, .booking-table tbody td:last-child form').forEach(function (node) {
            node.addEventListener('click', function (event) {
                event.stopPropagation();
            });
        });

        @foreach($bookings as $booking)
            (function () {
                var editModal = document.getElementById('editBooking{{ $booking->id }}');
                var detailModal = document.getElementById('bookingDetail{{ $booking->id }}');
                var meta = document.getElementById('aoBookingEditMeta{{ $booking->id }}');

                // Keep Bootstrap's expected .modal-content structure after replacing the modal body.
                if (editModal) {
                    var editContent = editModal.querySelector('.ao-booking-modal-content');
                    if (editContent) {
                        editContent.classList.add('modal-content');
                    }
                }

                if (detailModal) {
                    var detailContent = detailModal.querySelector('.ao-booking-modal-content');
                    if (detailContent) {
                        detailContent.classList.add('modal-content');
                    }
                }

                if (!editModal || !meta) {
                    return;
                }

                var select = editModal.querySelector('select[name="session_event_id"]');
                var currentId = meta.dataset.currentSessionId || '';
                var currentLabel = meta.dataset.currentSessionLabel || 'Current Session';

                if (!select || !currentId) {
                    return;
                }

                var currentOption = Array.from(select.options).find(function (option) {
                    return String(option.value) === String(currentId);
                });

                if (!currentOption) {
                    currentOption = new Option(currentLabel, currentId, true, true);
                    // Put the current booked session first so it is obvious what is selected.
                    select.insertBefore(currentOption, select.firstChild);
                }

                currentOption.selected = true;
                select.value = String(currentId);

                // Re-apply the current value every time Edit opens. This prevents a previous
                // unsaved selection from remaining after the modal is closed and opened again.
                editModal.addEventListener('show.bs.modal', function () {
                    select.value = String(currentId);
                });
            })();
        @endforeach
    });
});
</script>
@endif
