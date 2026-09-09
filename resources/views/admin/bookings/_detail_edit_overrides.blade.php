@if(isset($bookings))
<style>
    :root {
        --ao-booking-accent: #f3282c;
        --ao-booking-soft: #fff1f1;
        --ao-booking-ink: #171e2d;
        --ao-booking-muted: #6b7480;
        --ao-booking-border: #dde2e8;
    }

    .ao-booking-detail-dialog {
        max-width: 760px;
    }

    .ao-booking-edit-dialog {
        max-width: 800px;
    }

    .ao-booking-modal-content {
        overflow: hidden;
        border: 0;
        border-radius: 22px;
        background: #fff;
        box-shadow: 0 24px 70px rgba(15, 23, 42, .24);
    }

    .ao-booking-modal-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        padding: 22px 28px;
        border-bottom: 1px solid #e8ebef;
        background: #fff;
    }

    .ao-booking-modal-title {
        margin: 0;
        color: var(--ao-booking-ink);
        font-size: 21px;
        font-weight: 900;
        line-height: 1.15;
    }

    .ao-booking-modal-subtitle {
        margin-top: 5px;
        color: var(--ao-booking-muted);
        font-size: 11px;
        font-weight: 700;
        line-height: 1.4;
    }

    .ao-booking-modal-close {
        display: inline-flex;
        flex: 0 0 38px;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        padding: 0;
        border: 0;
        border-radius: 50%;
        background: #f6f7f9;
        color: #606873;
        font-size: 18px;
        cursor: pointer;
        transition: .18s ease;
    }

    .ao-booking-modal-close:hover {
        background: #111;
        color: #fff;
        transform: rotate(4deg);
    }

    .ao-booking-modal-body {
        padding: 14px 20px 20px;
        background: #f8f9fb;
    }

    .ao-booking-summary-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        margin-bottom: 14px;
    }

    .ao-booking-summary-card,
    .ao-booking-section,
    .ao-booking-edit-card {
        border: 1px solid var(--ao-booking-border);
        border-radius: 14px;
        background: #fff;
    }

    .ao-booking-summary-card {
        min-height: 66px;
        padding: 13px 12px;
    }

    .ao-booking-summary-label,
    .ao-booking-section-label,
    .ao-booking-edit-label {
        color: #6a7481;
        font-size: 10px;
        font-weight: 900;
        line-height: 1.2;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .ao-booking-summary-value {
        display: block;
        margin-top: 7px;
        overflow-wrap: anywhere;
        color: var(--ao-booking-ink);
        font-size: 18px;
        font-weight: 900;
        line-height: 1.15;
    }

    .ao-booking-section-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .ao-booking-section {
        padding: 13px;
    }

    .ao-booking-section-title {
        margin-bottom: 9px;
        color: #66717e;
        font-size: 10px;
        font-weight: 900;
        letter-spacing: .09em;
        text-transform: uppercase;
    }

    .ao-booking-info-row {
        display: grid;
        grid-template-columns: minmax(100px, .8fr) minmax(0, 1.4fr);
        gap: 12px;
        align-items: start;
        padding: 8px 0;
        border-bottom: 1px solid #edf0f3;
    }

    .ao-booking-info-row:last-child {
        border-bottom: 0;
    }

    .ao-booking-info-label {
        color: #737d88;
        font-size: 12px;
        font-weight: 800;
    }

    .ao-booking-info-value {
        overflow-wrap: anywhere;
        color: var(--ao-booking-ink);
        font-size: 12px;
        font-weight: 900;
        text-align: right;
    }

    .ao-booking-edit-body {
        padding: 22px 28px;
        background: #f8f9fb;
    }

    .ao-booking-edit-card {
        padding: 18px;
    }

    .ao-booking-edit-current {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .ao-booking-edit-value {
        margin-top: 8px;
        color: var(--ao-booking-ink);
        font-size: 15px;
        font-weight: 900;
    }

    .ao-booking-edit-select {
        width: 100%;
        min-height: 46px;
        margin-top: 9px;
        padding: 0 12px;
        border: 1px solid #ccd3dc;
        border-radius: 12px;
        outline: 0;
        background: #fff;
        color: var(--ao-booking-ink);
        font-size: 14px;
        font-weight: 800;
        box-shadow: none;
    }

    .ao-booking-edit-select:focus {
        border-color: var(--ao-booking-accent);
        box-shadow: 0 0 0 3px rgba(243, 40, 44, .10);
    }

    .ao-booking-modal-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding: 18px 28px;
        border-top: 1px solid #e8ebef;
        background: #fff;
    }

    .ao-booking-btn-close,
    .ao-booking-btn-save {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 0 18px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 900;
        line-height: 1;
        cursor: pointer;
        transition: .18s ease;
    }

    .ao-booking-btn-close {
        border: 1px solid #8c95a0;
        background: #fff;
        color: #66717e;
    }

    .ao-booking-btn-close:hover {
        border-color: #111;
        color: #111;
    }

    .ao-booking-btn-save {
        gap: 7px;
        border: 0;
        background: var(--ao-booking-accent);
        color: #fff;
        box-shadow: 0 10px 24px rgba(243, 40, 44, .20);
    }

    .ao-booking-btn-save:hover {
        background: #d91f23;
        color: #fff;
        transform: translateY(-1px);
    }

    .ao-booking-btn-save:disabled {
        opacity: .5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    @media (max-width: 767px) {
        .ao-booking-detail-dialog,
        .ao-booking-edit-dialog {
            max-width: none;
            margin: 8px;
        }

        .ao-booking-modal-header,
        .ao-booking-edit-body,
        .ao-booking-modal-footer {
            padding-left: 18px;
            padding-right: 18px;
        }

        .ao-booking-summary-grid,
        .ao-booking-section-grid,
        .ao-booking-edit-current {
            grid-template-columns: 1fr;
        }

        .ao-booking-info-row {
            grid-template-columns: 1fr;
            gap: 3px;
        }

        .ao-booking-info-value {
            text-align: left;
        }
    }
</style>

@foreach($bookings as $booking)
    @php
        $session = $booking->sessionEvent;
        $parent = $booking->customer;
        $child = $booking->child;

        $parentName = trim((string) ($parent->first_name ?? '') . ' ' . (string) ($parent->last_name ?? ''));
        $childName = trim((string) ($child->first_name ?? '') . ' ' . (string) ($child->last_name ?? ''));
        $parentName = $parentName !== '' ? $parentName : '-';
        $childName = $childName !== '' ? $childName : '-';

        $availableCredits = $parent
            ? (int) $parent->credits()->available()->sum('remaining_classes')
            : 0;

        $sessionName = $session ? ($session->training_type ?: ($session->name ?: 'Training Session')) : '-';
        $heldDate = $session && $session->event_date
            ? \Carbon\Carbon::parse($session->event_date)->format('M d, Y')
            : '-';

        $startTime = $session && $session->start_time
            ? \Carbon\Carbon::parse($session->start_time)->format('g:i A')
            : '-';
        $endTime = $session && $session->end_time
            ? \Carbon\Carbon::parse($session->end_time)->format('g:i A')
            : '-';
        $sessionTime = $startTime . ' - ' . $endTime;

        $location = $session
            ? trim((string) ($session->location ?? ''))
            : '';
        if ($location === '' && $session) {
            $location = collect([$session->street_address ?? null, $session->city ?? null])->filter()->implode(', ');
        }
        $location = $location !== '' ? $location : '-';

        $instructor = trim((string) ($session->instructor ?? '')) ?: '-';
        $capacity = $session && (int) ($session->capacity ?? 0) > 0
            ? (string) (int) $session->capacity
            : 'Unlimited';
        $status = ucfirst(str_replace('_', ' ', (string) ($booking->status ?: 'booked')));
        $bookedAt = $booking->booked_at
            ? \Carbon\Carbon::parse($booking->booked_at)->format('M d, Y')
            : ($booking->created_at ? \Carbon\Carbon::parse($booking->created_at)->format('M d, Y') : '-');

        $editSessions = collect($manualSessions ?? [])->filter(function ($candidate) use ($booking) {
            $candidate = is_array($candidate) ? $candidate : (array) $candidate;
            return empty($candidate['full'])
                && empty($candidate['expired'])
                && (int) ($candidate['id'] ?? 0) !== (int) $booking->session_event_id;
        })->values();
    @endphp

    <template id="aoBookingDetailTemplate{{ $booking->id }}">
        <div class="ao-booking-modal-content">
            <div class="ao-booking-modal-header">
                <div>
                    <h5 class="ao-booking-modal-title">Booking Details</h5>
                    <div class="ao-booking-modal-subtitle">{{ $parentName }} &middot; {{ $booking->booking_no ?: 'Booking' }}</div>
                </div>
                <button type="button" class="ao-booking-modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="ao-booking-modal-body">
                <div class="ao-booking-summary-grid">
                    <div class="ao-booking-summary-card">
                        <div class="ao-booking-summary-label">Parent</div>
                        <span class="ao-booking-summary-value">{{ $parentName }}</span>
                    </div>
                    <div class="ao-booking-summary-card">
                        <div class="ao-booking-summary-label">Credit Available</div>
                        <span class="ao-booking-summary-value">{{ $availableCredits }}</span>
                    </div>
                    <div class="ao-booking-summary-card">
                        <div class="ao-booking-summary-label">Booking No</div>
                        <span class="ao-booking-summary-value">{{ $booking->booking_no ?: '-' }}</span>
                    </div>
                </div>

                <div class="ao-booking-section-grid">
                    <section class="ao-booking-section">
                        <div class="ao-booking-section-title">Parent Details</div>
                        <div class="ao-booking-info-row"><span class="ao-booking-info-label">Name</span><span class="ao-booking-info-value">{{ $parentName }}</span></div>
                        <div class="ao-booking-info-row"><span class="ao-booking-info-label">Email</span><span class="ao-booking-info-value">{{ $parent->email ?? '-' }}</span></div>
                        <div class="ao-booking-info-row"><span class="ao-booking-info-label">Phone</span><span class="ao-booking-info-value">{{ $parent->phone ?? '-' }}</span></div>
                        <div class="ao-booking-info-row"><span class="ao-booking-info-label">Credit Available</span><span class="ao-booking-info-value">{{ $availableCredits }}</span></div>
                    </section>

                    <section class="ao-booking-section">
                        <div class="ao-booking-section-title">Child Details</div>
                        <div class="ao-booking-info-row"><span class="ao-booking-info-label">Child</span><span class="ao-booking-info-value">{{ $childName }}</span></div>
                        <div class="ao-booking-info-row"><span class="ao-booking-info-label">Grade</span><span class="ao-booking-info-value">{{ $child->grade ?? '-' }}</span></div>
                        <div class="ao-booking-info-row"><span class="ao-booking-info-label">Position</span><span class="ao-booking-info-value">{{ $child->position ?? '-' }}</span></div>
                    </section>

                    <section class="ao-booking-section">
                        <div class="ao-booking-section-title">Booked Session</div>
                        <div class="ao-booking-info-row"><span class="ao-booking-info-label">Session Name</span><span class="ao-booking-info-value">{{ $sessionName }}</span></div>
                        <div class="ao-booking-info-row"><span class="ao-booking-info-label">Held Date</span><span class="ao-booking-info-value">{{ $heldDate }}</span></div>
                        <div class="ao-booking-info-row"><span class="ao-booking-info-label">Time</span><span class="ao-booking-info-value">{{ $sessionTime }}</span></div>
                        <div class="ao-booking-info-row"><span class="ao-booking-info-label">Location</span><span class="ao-booking-info-value">{{ $location }}</span></div>
                        <div class="ao-booking-info-row"><span class="ao-booking-info-label">Instructor</span><span class="ao-booking-info-value">{{ $instructor }}</span></div>
                        <div class="ao-booking-info-row"><span class="ao-booking-info-label">Capacity</span><span class="ao-booking-info-value">{{ $capacity }}</span></div>
                    </section>

                    <section class="ao-booking-section">
                        <div class="ao-booking-section-title">Booking Record</div>
                        <div class="ao-booking-info-row"><span class="ao-booking-info-label">Booking No</span><span class="ao-booking-info-value">{{ $booking->booking_no ?: '-' }}</span></div>
                        <div class="ao-booking-info-row"><span class="ao-booking-info-label">Status</span><span class="ao-booking-info-value">{{ $status }}</span></div>
                        <div class="ao-booking-info-row"><span class="ao-booking-info-label">Booked At</span><span class="ao-booking-info-value">{{ $bookedAt }}</span></div>
                    </section>
                </div>
            </div>
        </div>
    </template>

    <template id="aoBookingEditTemplate{{ $booking->id }}">
        <form method="POST" action="{{ route('admin.bookings.session-wise.update-session', $booking) }}" class="ao-booking-modal-content">
            @csrf
            @method('PUT')

            <div class="ao-booking-modal-header">
                <div>
                    <h5 class="ao-booking-modal-title">Edit Booked Session</h5>
                    <div class="ao-booking-modal-subtitle">{{ $booking->booking_no ?: 'Booking' }} &middot; {{ $parentName }} &middot; {{ $childName }}</div>
                </div>
                <button type="button" class="ao-booking-modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="ao-booking-edit-body">
                <div class="ao-booking-edit-card mb-3">
                    <div class="ao-booking-edit-current">
                        <div>
                            <div class="ao-booking-edit-label">Current Session</div>
                            <div class="ao-booking-edit-value">{{ $sessionName }}</div>
                        </div>
                        <div>
                            <div class="ao-booking-edit-label">Current Date</div>
                            <div class="ao-booking-edit-value">{{ $heldDate }}</div>
                        </div>
                    </div>
                </div>

                <div class="ao-booking-edit-card">
                    <label class="ao-booking-edit-label" for="ao_booking_session_event_id_{{ $booking->id }}">Select New Session</label>
                    <select name="session_event_id" id="ao_booking_session_event_id_{{ $booking->id }}" class="ao-booking-edit-select" required>
                        <option value="">Choose a session</option>
                        @foreach($editSessions as $editSession)
                            @php
                                $editSession = is_array($editSession) ? $editSession : (array) $editSession;
                                $editTitle = $editSession['title'] ?? $editSession['name'] ?? $editSession['training_type'] ?? 'Training Session';
                                $editDate = $editSession['date_label'] ?? $editSession['date'] ?? '-';
                                $editTime = $editSession['time'] ?? '';
                                $editLeft = array_key_exists('left', $editSession) && $editSession['left'] !== null
                                    ? ' · ' . $editSession['left'] . ' left'
                                    : '';
                            @endphp
                            <option value="{{ $editSession['id'] }}">{{ $editTitle }} — {{ $editDate }} @if($editTime) — {{ $editTime }} @endif{{ $editLeft }}</option>
                        @endforeach
                    </select>
                    @if($editSessions->isEmpty())
                        <div class="small text-muted mt-2">No other available sessions are currently open for this booking.</div>
                    @endif
                </div>
            </div>

            <div class="ao-booking-modal-footer">
                <button type="button" class="ao-booking-btn-close" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="ao-booking-btn-save" @disabled($editSessions->isEmpty())>
                    <i class="fa-solid fa-check"></i>
                    Update Session
                </button>
            </div>
        </form>
    </template>
@endforeach

<script>
document.addEventListener('DOMContentLoaded', function () {
    @foreach($bookings as $booking)
        (function () {
            const detailModal = document.getElementById('bookingDetail{{ $booking->id }}');
            const detailTemplate = document.getElementById('aoBookingDetailTemplate{{ $booking->id }}');
            if (detailModal && detailTemplate) {
                const dialog = detailModal.querySelector('.modal-dialog');
                const content = detailModal.querySelector('.modal-content');
                if (dialog) {
                    dialog.className = 'modal-dialog modal-dialog-centered ao-booking-detail-dialog';
                }
                if (content) {
                    content.replaceWith(detailTemplate.content.firstElementChild.cloneNode(true));
                }
            }

            const editModal = document.getElementById('editBooking{{ $booking->id }}');
            const editTemplate = document.getElementById('aoBookingEditTemplate{{ $booking->id }}');
            if (editModal && editTemplate) {
                const dialog = editModal.querySelector('.modal-dialog');
                const content = editModal.querySelector('.modal-content');
                if (dialog) {
                    dialog.className = 'modal-dialog modal-dialog-centered ao-booking-edit-dialog';
                }
                if (content) {
                    content.replaceWith(editTemplate.content.firstElementChild.cloneNode(true));
                }
            }
        })();
    @endforeach
});
</script>
@endif
