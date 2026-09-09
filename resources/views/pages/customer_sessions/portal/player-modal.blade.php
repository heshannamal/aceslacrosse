@php
    $p = $player ?? null;
    $selectedPositions = collect(explode(',', (string)($p->position ?? '')))->map(fn($v)=>trim($v))->filter()->all();
@endphp
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content" style="border:0;border-radius:22px;overflow:hidden">
        <div class="p-3 d-flex align-items-center justify-content-between" style="background:#050505;color:#fff"><strong>{{ $title }}</strong><button class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
        <form method="POST" action="{{ $action }}">@csrf @if($method!=='POST') @method($method) @endif
            <div class="modal-body p-4"><div class="row g-3">
                <div class="col-md-6"><label class="form-label fw-bold">First Name *</label><input class="form-control rounded-3" name="first_name" value="{{ old('first_name',$p->first_name ?? '') }}" required></div>
                <div class="col-md-6"><label class="form-label fw-bold">Last Name</label><input class="form-control rounded-3" name="last_name" value="{{ old('last_name',$p->last_name ?? '') }}"></div>
                <div class="col-md-6"><label class="form-label fw-bold">Team</label><input class="form-control rounded-3" name="team" value="{{ old('team',$p->team ?? '') }}"></div>
                <div class="col-md-6"><label class="form-label fw-bold">Spring Team</label><input class="form-control rounded-3" name="spring_team" value="{{ old('spring_team',$p->spring_team ?? '') }}"></div>
                <div class="col-md-6"><label class="form-label fw-bold">Grade</label><input class="form-control rounded-3" name="grade" value="{{ old('grade',$p->grade ?? '') }}"></div>
                <div class="col-md-6"><label class="form-label fw-bold">Birthdate</label><input class="form-control rounded-3" type="date" name="birthdate" value="{{ old('birthdate', isset($p->birthdate)?$p->birthdate->format('Y-m-d'):'') }}"></div>
                <div class="col-12"><label class="form-label fw-bold">Positions</label><div class="pf-position">@foreach(['Attack','Middie','Defense','Goalie'] as $pos)<label><input type="checkbox" data-position-checkbox value="{{ $pos }}" {{ in_array($pos,$selectedPositions,true)?'checked':'' }}> {{ $pos }}</label>@endforeach</div><input type="hidden" name="position" data-position-value value="{{ $p->position ?? '' }}"></div>
            </div></div>
            <div class="modal-footer border-0"><button class="btn btn-outline-dark rounded-pill px-4" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn rounded-pill px-4 text-white fw-bold" style="background:#f3282c" type="submit">Save Player</button></div>
        </form>
    </div></div>
</div>
@push('scripts')
<script>document.addEventListener('DOMContentLoaded',function(){var modal=document.getElementById(@json($modalId));if(!modal)return;var hidden=modal.querySelector('[data-position-value]');var checks=modal.querySelectorAll('[data-position-checkbox]');function sync(){hidden.value=Array.from(checks).filter(c=>c.checked).map(c=>c.value).join(', ')}checks.forEach(c=>c.addEventListener('change',sync));sync();});</script>
@endpush
