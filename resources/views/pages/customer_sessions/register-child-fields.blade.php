<div class="training-register-column child-column">
    <div class="training-form-section-title">Child Details</div>

    <div class="training-field-grid">
        <div class="training-field">
            <label for="child_first_name">First Name <span>*</span></label>
            <div class="training-input-wrap">
                <i class="fa-regular fa-user"></i>
                <input
                    id="child_first_name"
                    name="child_first_name"
                    value="{{ old('child_first_name') }}"
                    autocomplete="given-name"
                    required
                >
            </div>
        </div>

        <div class="training-field">
            <label for="child_last_name">Last Name</label>
            <div class="training-input-wrap">
                <i class="fa-regular fa-user"></i>
                <input
                    id="child_last_name"
                    name="child_last_name"
                    value="{{ old('child_last_name') }}"
                    autocomplete="family-name"
                >
            </div>
        </div>
    </div>

    <div class="training-field-grid">
        <div class="training-field">
            <label for="child_team">Team</label>
            <div class="training-input-wrap">
                <i class="fa-solid fa-people-group"></i>
                <input id="child_team" name="child_team" value="{{ old('child_team') }}">
            </div>
        </div>

        <div class="training-field">
            <label for="child_spring_team">Spring Team</label>
            <div class="training-input-wrap">
                <i class="fa-solid fa-people-group"></i>
                <input id="child_spring_team" name="child_spring_team" value="{{ old('child_spring_team') }}">
            </div>
        </div>
    </div>

    <div class="training-field-grid">
        <div class="training-field">
            <label for="child_grade">Grade</label>
            <div class="training-input-wrap">
                <i class="fa-solid fa-graduation-cap"></i>
                <input id="child_grade" name="child_grade" value="{{ old('child_grade') }}">
            </div>
        </div>

        <div class="training-field">
            <label for="child_birthdate">Birthdate</label>
            <div class="training-input-wrap">
                <i class="fa-regular fa-calendar-days"></i>
                <input id="child_birthdate" type="date" name="child_birthdate" value="{{ old('child_birthdate') }}">
            </div>
        </div>
    </div>

    @php
        $oldPositions = collect(explode(',', (string) old('child_position')))
            ->map(fn ($position) => trim($position))
            ->filter()
            ->values();
    @endphp

    <div class="training-field mb-0">
        <label>Positions</label>
        <input type="hidden" name="child_position" id="child_position" value="{{ old('child_position') }}">

        <div class="training-position-options" data-position-group>
            @foreach(['Attack', 'Middie', 'Defense', 'Goalie'] as $position)
                <label class="training-position-option">
                    <input
                        type="checkbox"
                        value="{{ $position }}"
                        @checked($oldPositions->contains($position))
                    >
                    <span>{{ $position }}</span>
                </label>
            @endforeach
        </div>
    </div>
</div>

<style>
    .training-position-options {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .training-position-option {
        margin: 0 !important;
        display: inline-flex !important;
        align-items: center;
        gap: 8px;
        padding: 9px 14px;
        border: 1px solid #d9dee7;
        border-radius: 999px;
        background: #fff;
        color: #111827 !important;
        font-size: 13px !important;
        font-weight: 800 !important;
        cursor: pointer;
        transition: .18s ease;
    }

    .training-position-option:hover {
        border-color: #f3282c;
    }

    .training-position-option:has(input:checked) {
        border-color: #f3282c;
        background: #fff1f1;
        color: #f3282c !important;
    }

    .training-position-option input {
        margin: 0;
        accent-color: #f3282c;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const group = document.querySelector('[data-position-group]');
    const hidden = document.getElementById('child_position');

    if (!group || !hidden) return;

    function syncPositions() {
        hidden.value = Array.from(group.querySelectorAll('input[type="checkbox"]:checked'))
            .map(function (input) { return input.value; })
            .join(', ');
    }

    group.addEventListener('change', syncPositions);
    syncPositions();
});
</script>
