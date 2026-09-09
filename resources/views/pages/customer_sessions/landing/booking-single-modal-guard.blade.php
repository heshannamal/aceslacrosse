<script>
document.addEventListener('DOMContentLoaded', function () {
    // The Baddies-style booking modal is the only booking UI. Keep the old
    // per-session Bootstrap modal in the DOM only as a data source for the
    // migrated flow, but never allow Bootstrap to open it.
    document.querySelectorAll('.training-red-button[data-bs-target^="#trainingBook"]').forEach(function (button) {
        button.removeAttribute('data-bs-toggle');
    });

    const packageCards = document.getElementById('aoPackageCards');
    if (packageCards) {
        packageCards.style.display = 'none';
    }

    // Calendar entries must open the same Baddies-style booking modal instead
    // of creating a second Bootstrap modal instance.
    document.addEventListener('click', function (event) {
        const calendarLink = event.target.closest('[data-calendar-book]');
        if (!calendarLink) return;

        const id = String(calendarLink.getAttribute('data-calendar-book') || '').replace(/[^0-9]/g, '');
        if (!id) return;

        const bookingButton = document.querySelector(
            '.training-red-button[data-bs-target="#trainingBook' + id + '"]'
        );

        if (!bookingButton) return;

        event.preventDefault();
        event.stopImmediatePropagation();
        bookingButton.click();
    }, true);
}, { once: true });
</script>
