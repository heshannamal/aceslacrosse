@php
    $acesLocationDisplaySessions = collect($sessions ?? [])->map(function ($session) {
        $addressParts = collect([
            $session->street_address ?? null,
            $session->city ?? null,
        ])->map(function ($value) {
            return trim((string) $value);
        })->filter()->unique()->values();

        $displayLocation = $addressParts->isNotEmpty()
            ? $addressParts->implode(', ')
            : trim((string) ($session->location ?? ''));

        return [
            'id' => (int) ($session->id ?? 0),
            'location' => $displayLocation !== '' ? $displayLocation : 'Location coming soon',
        ];
    })->values();
@endphp

@if($acesLocationDisplaySessions->isNotEmpty())
<script>
document.addEventListener('DOMContentLoaded', function () {
    var sessions = @json($acesLocationDisplaySessions);
    var cards = Array.prototype.slice.call(document.querySelectorAll('.training-session-grid > .training-session-card'));

    function setLocationInDetails(modal, value) {
        if (!modal) return;
        modal.querySelectorAll('.training-modal-details-grid > div').forEach(function (item) {
            var label = item.querySelector('span');
            var strong = item.querySelector('strong');
            if (label && strong && label.textContent.trim().toLowerCase() === 'location') {
                strong.textContent = value;
            }
        });
    }

    sessions.forEach(function (session, index) {
        var value = session.location || 'Location coming soon';
        var card = cards[index];

        if (card) {
            card.querySelectorAll('.training-info-box').forEach(function (box) {
                var label = box.querySelector(':scope > span');
                var strong = box.querySelector(':scope > strong');
                if (label && strong && label.textContent.trim().toLowerCase() === 'location') {
                    var icon = strong.querySelector('i');
                    strong.textContent = '';
                    if (icon) strong.appendChild(icon);
                    strong.appendChild(document.createTextNode(value));
                }
            });
        }

        setLocationInDetails(document.getElementById('trainingDetails' + session.id), value);

        var bookingModal = document.getElementById('trainingBook' + session.id);
        if (bookingModal) {
            var confirmCard = bookingModal.querySelector('.training-confirm-card');
            if (confirmCard) {
                var paragraphs = confirmCard.querySelectorAll('p');
                if (paragraphs.length > 1) {
                    paragraphs[paragraphs.length - 1].textContent = value;
                }
            }
        }
    });
});
</script>
@endif
