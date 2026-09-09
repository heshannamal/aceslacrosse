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

<script>
document.addEventListener('DOMContentLoaded', function () {
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
