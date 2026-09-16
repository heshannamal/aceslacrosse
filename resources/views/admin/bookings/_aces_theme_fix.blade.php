<style>
/* ACES theme normalization for the Bookings page and booking modals. */
:root {
    --aces-booking-accent: #611eb2;
    --aces-booking-accent-dark: #4d168f;
    --aces-booking-soft: #f4edfc;
    --aces-booking-ink: #171021;
}

/* Main bookings page */
.booking-page {
    --accent: var(--aces-booking-accent) !important;
    --soft: var(--aces-booking-soft) !important;
}

.booking-page .booking-count,
.booking-page .booking-count-badge {
    background: var(--aces-booking-soft) !important;
    color: var(--aces-booking-accent) !important;
}

.booking-page .btn-primary,
.booking-page .add-booking-btn,
.booking-page .filter-card .btn-primary {
    background: var(--aces-booking-accent) !important;
    border-color: var(--aces-booking-accent) !important;
    color: #fff !important;
}

.booking-page .btn-primary:hover,
.booking-page .add-booking-btn:hover,
.booking-page .filter-card .btn-primary:hover {
    background: var(--aces-booking-accent-dark) !important;
    border-color: var(--aces-booking-accent-dark) !important;
}

.booking-page .filter-input:focus,
.booking-page .filter-select:focus {
    border-color: var(--aces-booking-accent) !important;
    box-shadow: 0 0 0 3px rgba(97, 30, 178, .12) !important;
}

/* Add Booking wizard */
.ao-manual-head {
    background: linear-gradient(90deg, #fff, #faf7ff) !important;
    border-bottom-color: #eee8f3 !important;
}

.ao-manual-kicker,
.ao-manual-select-icon,
.ao-credit-line i,
.ao-credit-line strong {
    color: var(--aces-booking-accent) !important;
}

.ao-manual-close:hover {
    background: var(--aces-booking-accent) !important;
}

.ao-manual-step.active span,
.ao-manual-step.done span {
    background: var(--aces-booking-accent) !important;
    color: #fff !important;
}

.ao-manual-step.done {
    background: var(--aces-booking-soft) !important;
    color: var(--aces-booking-accent) !important;
}

.ao-manual-select:focus,
.ao-manual-input:focus {
    border-color: var(--aces-booking-accent) !important;
    box-shadow: 0 0 0 3px rgba(97, 30, 178, .10) !important;
}

.ao-choice.active,
.ao-session-card:hover,
.ao-session-card.selected {
    border-color: var(--aces-booking-accent) !important;
    background: #faf7ff !important;
    box-shadow: 0 0 0 3px rgba(97, 30, 178, .07) !important;
}

.ao-choice input,
.ao-position-pill input {
    accent-color: var(--aces-booking-accent) !important;
}

.ao-manual-btn-red,
#aoManualSubmit {
    background: var(--aces-booking-accent) !important;
    color: #fff !important;
    box-shadow: 0 8px 20px rgba(97, 30, 178, .22) !important;
}

.ao-manual-btn-red:hover,
#aoManualSubmit:hover {
    background: var(--aces-booking-accent-dark) !important;
    color: #fff !important;
}

/* Keep Next as the dark secondary action used throughout ACES. */
.ao-manual-btn-dark {
    background: var(--aces-booking-ink) !important;
    color: #fff !important;
}

.ao-manual-btn-dark:hover {
    background: #2b2036 !important;
}

/* Booking detail/edit modal */
.ao-booking-modal-content,
.ao-booking-edit-dialog {
    --ao-booking-accent: var(--aces-booking-accent) !important;
    --ao-booking-soft: var(--aces-booking-soft) !important;
}

.ao-booking-edit-select:focus {
    border-color: var(--aces-booking-accent) !important;
    box-shadow: 0 0 0 3px rgba(97, 30, 178, .10) !important;
}

.ao-booking-btn-save {
    background: var(--aces-booking-accent) !important;
    color: #fff !important;
    box-shadow: 0 10px 24px rgba(97, 30, 178, .20) !important;
}

.ao-booking-btn-save:hover:not(:disabled) {
    background: var(--aces-booking-accent-dark) !important;
    color: #fff !important;
}

.ao-booking-btn-save:disabled {
    background: #b896d9 !important;
    color: #fff !important;
    opacity: .72 !important;
    box-shadow: none !important;
}

/* Older/base edit modal fallback before the replacement template is applied. */
#addBookingModal .btn-primary,
[id^="editBooking"] .btn-primary {
    background: var(--aces-booking-accent) !important;
    border-color: var(--aces-booking-accent) !important;
    color: #fff !important;
}

#addBookingModal .btn-primary:hover,
[id^="editBooking"] .btn-primary:hover {
    background: var(--aces-booking-accent-dark) !important;
    border-color: var(--aces-booking-accent-dark) !important;
}

/* Action icons */
.booking-page .icon-btn.btn-outline-primary,
.booking-page .action-icon-btn.edit {
    color: var(--aces-booking-accent) !important;
    border-color: var(--aces-booking-accent) !important;
    background: #fff !important;
}

.booking-page .icon-btn.btn-outline-primary:hover,
.booking-page .action-icon-btn.edit:hover {
    background: var(--aces-booking-accent) !important;
    color: #fff !important;
}
</style>
