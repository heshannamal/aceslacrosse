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

        $mapQuery = '';
        if (!empty($session->location_lat) && !empty($session->location_lng)) {
            $mapQuery = trim((string) $session->location_lat) . ',' . trim((string) $session->location_lng);
        } elseif (trim((string) ($session->location ?? '')) !== '') {
            $mapQuery = trim((string) $session->location);
        } else {
            $mapQuery = $displayLocation;
        }

        return [
            'id' => (int) ($session->id ?? 0),
            'location' => $displayLocation !== '' ? $displayLocation : 'Location coming soon',
            'map_url' => $mapQuery !== ''
                ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($mapQuery)
                : null,
        ];
    })->values();

    $acesCalendarDisplaySessions = collect($calendarSessions ?? $sessions ?? [])->map(function ($session) {
        try {
            $date = !empty($session->event_date)
                ? \Carbon\Carbon::parse($session->event_date)->format('Y-m-d')
                : null;
            $start = !empty($session->start_time)
                ? \Carbon\Carbon::parse($session->start_time)->format('g:i A')
                : '';
            $end = !empty($session->end_time)
                ? \Carbon\Carbon::parse($session->end_time)->format('g:i A')
                : '';
        } catch (\Throwable $e) {
            $date = null;
            $start = '';
            $end = '';
        }

        $city = trim((string) ($session->city ?? ''));
        if ($city === '') {
            $city = trim((string) ($session->location ?? ''));
        }

        return [
            'id' => (int) ($session->id ?? 0),
            'date' => $date,
            'title' => $session->training_type ?: ($session->name ?: 'Training'),
            'time' => trim($start . ($end ? ' - ' . $end : '')),
            'city' => $city,
        ];
    })->filter(function ($session) {
        return !empty($session['date']);
    })->values();
@endphp

<style>
    .training-session-card .aces-session-map-link {
        display: inline-flex;
        align-items: flex-start;
        gap: 7px;
        color: inherit;
        text-decoration: none;
        line-height: 1.35;
    }
    .training-session-card .aces-session-map-link:hover {
        color: #611eb2;
        text-decoration: underline;
        text-underline-offset: 2px;
    }
    .training-session-card .aces-session-map-link i {
        margin-top: 2px;
        flex: 0 0 auto;
    }
    .training-calendar-event .training-calendar-city {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 3px;
        color: #475467;
        font-size: 9px;
        line-height: 1.2;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .training-calendar-event .training-calendar-city i {
        color: #611eb2;
        font-size: 9px;
        flex: 0 0 auto;
    }
</style>

@if($acesLocationDisplaySessions->isNotEmpty() || $acesCalendarDisplaySessions->isNotEmpty())
<script>
document.addEventListener('DOMContentLoaded', function () {
    var sessions = @json($acesLocationDisplaySessions);
    var calendarSessions = @json($acesCalendarDisplaySessions);
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
                if (!label || !strong || label.textContent.trim().toLowerCase() !== 'location') {
                    return;
                }

                var icon = strong.querySelector('i');
                strong.textContent = '';

                if (session.map_url) {
                    var link = document.createElement('a');
                    link.className = 'aces-session-map-link';
                    link.href = session.map_url;
                    link.target = '_blank';
                    link.rel = 'noopener noreferrer';
                    link.title = 'Open location in Google Maps';
                    if (icon) link.appendChild(icon);
                    link.appendChild(document.createTextNode(value));
                    strong.appendChild(link);
                } else {
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

    var grid = document.getElementById('trainingCalendarGrid');
    var monthLabel = document.getElementById('trainingCalendarMonth');
    var monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];

    function dateKey(date) {
        return date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0') + '-' + String(date.getDate()).padStart(2, '0');
    }

    function patchCalendarCities() {
        if (!grid || !monthLabel) return;

        var labelParts = (monthLabel.textContent || '').trim().split(/\s+/);
        if (labelParts.length < 2) return;

        var month = monthNames.indexOf(labelParts[0]);
        var year = parseInt(labelParts[1], 10);
        if (month < 0 || Number.isNaN(year)) return;

        var first = new Date(year, month, 1);
        var start = new Date(year, month, 1 - first.getDay());
        var cells = Array.prototype.slice.call(grid.querySelectorAll('.training-calendar-cell'));

        cells.forEach(function (cell, index) {
            var day = new Date(start);
            day.setDate(start.getDate() + index);
            var key = dateKey(day);
            var eventsForDay = calendarSessions.filter(function (session) {
                return session.date === key;
            });

            var eventNodes = Array.prototype.slice.call(cell.querySelectorAll('.training-calendar-event'));
            eventNodes.forEach(function (eventNode, eventIndex) {
                if (eventNode.querySelector('.training-calendar-city')) return;

                var titleNode = eventNode.querySelector('strong');
                var timeNode = eventNode.querySelector(':scope > span:not(.training-calendar-city)');
                var title = titleNode ? titleNode.textContent.trim() : '';
                var time = timeNode ? timeNode.textContent.trim() : '';

                var match = eventsForDay.find(function (session) {
                    return session.title === title && session.time === time;
                }) || eventsForDay[eventIndex];

                if (!match || !match.city) return;

                var cityRow = document.createElement('span');
                cityRow.className = 'training-calendar-city';
                cityRow.innerHTML = '<i class="fa-solid fa-location-dot" aria-hidden="true"></i><span></span>';
                cityRow.querySelector('span').textContent = match.city;

                var action = eventNode.querySelector('a');
                if (action) {
                    eventNode.insertBefore(cityRow, action);
                } else {
                    eventNode.appendChild(cityRow);
                }
            });
        });
    }

    patchCalendarCities();

    if (grid) {
        var calendarObserver = new MutationObserver(function () {
            patchCalendarCities();
        });
        calendarObserver.observe(grid, { childList: true, subtree: true });
    }
});
</script>
@endif
