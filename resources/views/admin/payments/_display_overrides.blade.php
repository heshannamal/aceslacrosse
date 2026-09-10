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

    // Shorten Order No in the payments table while preserving the full value
    // for hover/title and keeping server-side search unchanged.
    document.querySelectorAll('.pay-table tbody .payment-row').forEach(function (row) {
        var firstCell = row.children[0];
        if (!firstCell) {
            return;
        }

        var strong = firstCell.querySelector('strong');
        if (!strong) {
            return;
        }

        var fullCode = strong.textContent.trim();
        if (!fullCode) {
            return;
        }

        strong.title = fullCode;
        strong.dataset.fullReference = fullCode;
        strong.textContent = shortReference(fullCode);
    });

    // Payment modal headings currently use "Payment {full-order-no}".
    // Keep the label and show only the final order token.
    document.querySelectorAll('.payment-modal .modal-header h5').forEach(function (heading) {
        var text = heading.textContent.trim();
        if (!text) {
            return;
        }

        var fullCode = text.replace(/^Payment\s+/i, '').trim();
        if (!fullCode) {
            return;
        }

        heading.title = fullCode;
        heading.textContent = 'Payment ' + shortReference(fullCode);
    });

    // Booking references listed inside payment details should use the same
    // compact display when generated in BKG-YYYYMMDD-CODE format.
    document.querySelectorAll('.payment-modal .booking-item .small-muted').forEach(function (meta) {
        var text = meta.textContent || '';
        var match = text.match(/BKG-[A-Za-z0-9-]+/);
        if (!match) {
            return;
        }

        var fullCode = match[0];
        meta.title = fullCode;
        meta.textContent = text.replace(fullCode, shortReference(fullCode));
    });
});
</script>
