<style>
    #acesAddCreditsModal{--ac:#611eb2;--ac-dark:#4d168f;--ac-soft:#f4edfc;--ink:#171021}
    #acesAddCreditsModal .modal-dialog{max-width:760px}
    #acesAddCreditsModal .modal-content{border:0;border-radius:24px;overflow:hidden;box-shadow:0 30px 90px rgba(23,16,33,.22)}
    #acesAddCreditsModal .modal-header{padding:20px 24px;background:linear-gradient(135deg,#171021,#3b1b55);color:#fff;border:0}
    #acesAddCreditsModal .modal-body{padding:24px;background:#f8f7fa}
    #acesAddCreditsModal .modal-footer{padding:16px 24px;background:#fff;border-top:1px solid #ece8f1}
    .ac-stepper{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:22px}
    .ac-step{height:6px;border-radius:999px;background:#ded9e5;transition:.2s ease}.ac-step.active,.ac-step.done{background:var(--ac)}
    .ac-step-panel{display:none}.ac-step-panel.active{display:block}
    .ac-kicker{font-size:11px;font-weight:900;letter-spacing:.13em;color:var(--ac);text-transform:uppercase}
    .ac-title{font-size:22px;font-weight:900;color:var(--ink);margin:5px 0}.ac-sub{color:#697386;font-size:13px;margin-bottom:16px}
    .ac-search,.ac-custom{height:48px;border:1px solid #d8d4de;border-radius:13px;background:#fff;padding:0 13px;width:100%;font-weight:700;color:#24202b}
    .ac-search:focus,.ac-note:focus,.ac-custom:focus{outline:0;border-color:var(--ac);box-shadow:0 0 0 3px rgba(97,30,178,.1)}
    .ac-child-results{display:grid;gap:8px;max-height:285px;overflow-y:auto;padding:3px 3px 3px 0;margin-top:11px}
    .ac-child-result{width:100%;border:1px solid #ddd8e3;border-radius:14px;background:#fff;padding:12px 14px;text-align:left;display:flex;align-items:center;justify-content:space-between;gap:14px;transition:.16s ease}
    .ac-child-result:hover{border-color:#b58bdc;background:#fdfbff}.ac-child-result.active{border-color:var(--ac);background:var(--ac-soft);box-shadow:0 0 0 3px rgba(97,30,178,.08)}
    .ac-child-result strong{display:block;color:var(--ink);font-size:14px}.ac-child-result small{display:block;color:#6b7280;font-size:11px;margin-top:3px}.ac-child-check{width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#f1edf5;color:#9a8da7;flex:0 0 26px}.ac-child-result.active .ac-child-check{background:var(--ac);color:#fff}
    .ac-child-empty{padding:22px;text-align:center;border:1px dashed #d8d4de;border-radius:14px;color:#7b7284;background:#fff;margin-top:11px;font-weight:700}
    .ac-parent-list{display:grid;gap:10px}.ac-parent-card,.ac-credit-card{border:1px solid #ddd8e3;border-radius:15px;background:#fff;padding:14px;cursor:pointer;transition:.18s ease}.ac-parent-card:hover,.ac-credit-card:hover{border-color:#b58bdc}.ac-parent-card.active,.ac-credit-card.active{border-color:var(--ac);background:var(--ac-soft);box-shadow:0 0 0 3px rgba(97,30,178,.08)}
    .ac-parent-top{display:flex;justify-content:space-between;gap:12px}.ac-parent-card strong{color:var(--ink)}.ac-parent-card small{display:block;color:#6b7280;margin-top:3px}.ac-parent-role{font-size:10px;font-weight:900;color:var(--ac);text-transform:uppercase;letter-spacing:.08em}.ac-balance{margin-top:8px!important;color:var(--ac)!important;font-weight:900}
    .ac-credit-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}.ac-credit-card{text-align:center}.ac-credit-card strong{display:block;font-size:22px;color:var(--ink)}.ac-credit-card span{font-size:11px;color:#6b7280;font-weight:800}
    .ac-custom-wrap{margin-top:12px;display:none}.ac-note{width:100%;min-height:92px;border:1px solid #d8d4de;border-radius:13px;padding:12px 13px;resize:vertical}
    .ac-review{background:#fff;border:1px solid #e1d9ea;border-radius:17px;overflow:hidden}.ac-review-row{display:flex;justify-content:space-between;gap:20px;padding:12px 15px;border-bottom:1px solid #eeeaf2}.ac-review-row:last-child{border-bottom:0}.ac-review-row span{color:#74707c;font-size:12px;font-weight:800}.ac-review-row strong{text-align:right;color:var(--ink);font-size:13px}.ac-review-row.balance-new strong{color:var(--ac);font-size:18px}
    .ac-no-expiry{margin-top:14px;padding:12px 14px;border-radius:13px;background:#eefbf3;border:1px solid #ccebd8;color:#167044;font-weight:800;font-size:13px}
    .ac-btn{border:0;border-radius:999px;font-weight:900;padding:10px 20px}.ac-btn-primary{background:var(--ac);color:#fff}.ac-btn-primary:hover{background:var(--ac-dark);color:#fff}.ac-btn-light{background:#f1eef4;color:#302a38}.ac-loading{padding:35px;text-align:center;color:#6b7280;font-weight:700}
    @media(max-width:680px){.ac-credit-grid{grid-template-columns:repeat(2,1fr)}.ac-review-row{flex-direction:column;gap:3px}.ac-review-row strong{text-align:left}.ac-child-result{align-items:flex-start}}
</style>

<div class="modal fade" id="acesAddCreditsModal" tabindex="-1" aria-labelledby="acesAddCreditsTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="small text-uppercase fw-bold" style="letter-spacing:.12em;color:#d7baf1">ACES Training</div>
                    <h5 class="modal-title fw-black mb-0" id="acesAddCreditsTitle">Add Credits</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ac-stepper" aria-hidden="true">
                    <div class="ac-step active" data-ac-step-indicator="1"></div><div class="ac-step" data-ac-step-indicator="2"></div><div class="ac-step" data-ac-step-indicator="3"></div><div class="ac-step" data-ac-step-indicator="4"></div>
                </div>

                <div id="acCreditsLoading" class="ac-loading"><i class="fa-solid fa-circle-notch fa-spin me-2"></i>Loading active members...</div>
                <div id="acCreditsError" class="alert alert-danger d-none"></div>

                <div class="ac-step-panel" data-ac-panel="1">
                    <div class="ac-kicker">Step 1 of 4</div>
                    <div class="ac-title">Select an active child</div>
                    <div class="ac-sub">Search by player name, team, or grade. Matching players appear immediately below.</div>
                    <input id="acChildSearch" type="search" class="ac-search" autocomplete="off" placeholder="Search child, team, or grade...">
                    <div id="acChildResults" class="ac-child-results"></div>
                    <div id="acChildEmpty" class="ac-child-empty d-none">No matching children found.</div>
                </div>

                <div class="ac-step-panel" data-ac-panel="2">
                    <div class="ac-kicker">Step 2 of 4</div>
                    <div class="ac-title">Select Parent 1 or Parent 2</div>
                    <div class="ac-sub">Choose which parent record should receive the Training credit entry.</div>
                    <div id="acParentList" class="ac-parent-list"></div>
                </div>

                <div class="ac-step-panel" data-ac-panel="3">
                    <div class="ac-kicker">Step 3 of 4</div>
                    <div class="ac-title">Choose credits</div>
                    <div class="ac-sub">Select a preset amount or enter a custom number. Credits remain available until used.</div>
                    <div class="ac-credit-grid">
                        <button type="button" class="ac-credit-card" data-ac-credit="1"><strong>1</strong><span>CREDIT</span></button>
                        <button type="button" class="ac-credit-card" data-ac-credit="6"><strong>6</strong><span>CREDITS</span></button>
                        <button type="button" class="ac-credit-card" data-ac-credit="12"><strong>12</strong><span>CREDITS</span></button>
                        <button type="button" class="ac-credit-card" data-ac-credit="custom"><strong>+</strong><span>CUSTOM</span></button>
                    </div>
                    <div id="acCustomWrap" class="ac-custom-wrap"><label class="form-label small fw-bold mt-1">CUSTOM CREDITS</label><input id="acCustomCredits" type="number" min="1" max="10000" class="ac-custom" placeholder="Enter number of credits"></div>
                    <label class="form-label small fw-bold mt-3">INTERNAL NOTE <span class="text-muted fw-normal">(optional)</span></label>
                    <textarea id="acCreditNote" class="ac-note" placeholder="Reason, reference, or internal note..."></textarea>
                </div>

                <div class="ac-step-panel" data-ac-panel="4">
                    <div class="ac-kicker">Step 4 of 4</div>
                    <div class="ac-title">Review and confirm</div>
                    <div class="ac-sub">Confirm the credit adjustment before saving.</div>
                    <div class="ac-review">
                        <div class="ac-review-row"><span>Child</span><strong id="acReviewChild">—</strong></div>
                        <div class="ac-review-row"><span>Parent</span><strong id="acReviewParent">—</strong></div>
                        <div class="ac-review-row"><span>Credits to Add</span><strong id="acReviewCredits">—</strong></div>
                        <div class="ac-review-row"><span>Current Credit Balance</span><strong id="acReviewCurrent">0</strong></div>
                        <div class="ac-review-row balance-new"><span>New Credit Balance</span><strong id="acReviewNew">0</strong></div>
                        <div class="ac-review-row"><span>Internal Note</span><strong id="acReviewNote">—</strong></div>
                    </div>
                    <div class="ac-no-expiry"><i class="fa-solid fa-infinity me-2"></i>No Expiration — credits remain valid until used.</div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="ac-btn ac-btn-light" id="acBackBtn" style="visibility:hidden"><i class="fa-solid fa-arrow-left me-2"></i>Back</button>
                <div class="d-flex gap-2">
                    <button type="button" class="ac-btn ac-btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="ac-btn ac-btn-primary" id="acNextBtn">Continue <i class="fa-solid fa-arrow-right ms-2"></i></button>
                    <button type="button" class="ac-btn ac-btn-primary d-none" id="acConfirmBtn"><i class="fa-solid fa-circle-check me-2"></i>Confirm Credits</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var toolbar = document.querySelector('.member-head > .d-flex');
    if (toolbar && !document.getElementById('acesAddCreditsButton')) {
        var button = document.createElement('button');
        button.type = 'button';
        button.id = 'acesAddCreditsButton';
        button.className = 'btn accent-btn px-4';
        button.setAttribute('data-bs-toggle', 'modal');
        button.setAttribute('data-bs-target', '#acesAddCreditsModal');
        button.innerHTML = '<i class="fa-solid fa-coins me-2"></i>Add Credits';
        toolbar.insertBefore(button, toolbar.firstChild);
    }

    var modalEl = document.getElementById('acesAddCreditsModal');
    if (!modalEl) return;

    var optionsUrl = @json(route('admin.parents.booking.credits.options'));
    var storeUrl = @json(route('admin.parents.booking.credits.store'));
    var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    var children = [];
    var step = 1;
    var state = {child:null,parent:null,creditOption:null,credits:0,note:''};

    var loading = document.getElementById('acCreditsLoading');
    var errorBox = document.getElementById('acCreditsError');
    var childSearch = document.getElementById('acChildSearch');
    var childResults = document.getElementById('acChildResults');
    var childEmpty = document.getElementById('acChildEmpty');
    var parentList = document.getElementById('acParentList');
    var customWrap = document.getElementById('acCustomWrap');
    var customCredits = document.getElementById('acCustomCredits');
    var note = document.getElementById('acCreditNote');
    var backBtn = document.getElementById('acBackBtn');
    var nextBtn = document.getElementById('acNextBtn');
    var confirmBtn = document.getElementById('acConfirmBtn');

    function escapeHtml(value) {
        return String(value == null ? '' : value).replace(/[&<>"']/g, function(char){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot',"'":'&#039;'}[char]});
    }
    function showError(message){errorBox.textContent=message;errorBox.classList.remove('d-none')}
    function clearError(){errorBox.classList.add('d-none');errorBox.textContent=''}

    function setStep(number) {
        step = number;
        document.querySelectorAll('#acesAddCreditsModal [data-ac-panel]').forEach(function(panel){panel.classList.toggle('active',Number(panel.dataset.acPanel)===step)});
        document.querySelectorAll('#acesAddCreditsModal [data-ac-step-indicator]').forEach(function(indicator){var n=Number(indicator.dataset.acStepIndicator);indicator.classList.toggle('active',n===step);indicator.classList.toggle('done',n<step)});
        backBtn.style.visibility=step>1?'visible':'hidden';
        nextBtn.classList.toggle('d-none',step===4);
        confirmBtn.classList.toggle('d-none',step!==4);
        clearError();
        if(step===4)renderReview();
    }

    function renderChildren() {
        var needle=(childSearch.value||'').trim().toLowerCase();
        var matches=children.filter(function(child){
            return !needle || [child.name,child.team,child.spring_team,child.class_year].filter(Boolean).join(' ').toLowerCase().includes(needle);
        });
        childResults.innerHTML='';
        childEmpty.classList.toggle('d-none',matches.length>0);
        matches.forEach(function(child){
            var item=document.createElement('button');
            item.type='button';
            item.className='ac-child-result'+(state.child&&String(state.child.id)===String(child.id)?' active':'');
            var meta=[child.class_year,child.team,child.spring_team?('Spring: '+child.spring_team):null].filter(Boolean).join(' · ');
            item.innerHTML='<div><strong>'+escapeHtml(child.name)+'</strong><small>'+escapeHtml(meta||'Active player')+'</small></div><span class="ac-child-check"><i class="fa-solid fa-check"></i></span>';
            item.addEventListener('click',function(){state.child=child;state.parent=null;renderChildren()});
            childResults.appendChild(item);
        });
    }

    function resetWizard() {
        state={child:null,parent:null,creditOption:null,credits:0,note:''};
        childSearch.value='';parentList.innerHTML='';customCredits.value='';note.value='';customWrap.style.display='none';
        document.querySelectorAll('#acesAddCreditsModal .ac-credit-card').forEach(function(card){card.classList.remove('active')});
        renderChildren();setStep(1);window.setTimeout(function(){childSearch.focus()},180);
    }

    async function loadOptions() {
        loading.classList.remove('d-none');clearError();
        document.querySelectorAll('#acesAddCreditsModal .ac-step-panel').forEach(function(panel){panel.classList.remove('active')});
        try{
            var response=await fetch(optionsUrl,{headers:{Accept:'application/json'}});var data=await response.json();
            if(!response.ok||!data.status)throw new Error(data.message||'Could not load active members.');
            children=data.children||[];loading.classList.add('d-none');resetWizard();
        }catch(error){loading.classList.add('d-none');showError(error.message||'Could not load members.')}
    }

    function renderParents() {
        parentList.innerHTML='';
        if(!state.child)return;
        (state.child.parents||[]).forEach(function(parent){
            var card=document.createElement('button');card.type='button';card.className='ac-parent-card text-start';
            card.innerHTML='<div class="ac-parent-top"><div><strong>'+escapeHtml(parent.name)+'</strong><small>'+escapeHtml(parent.email||'')+(parent.phone?' · '+escapeHtml(parent.phone):'')+'</small></div><div class="ac-parent-role">'+escapeHtml(parent.relationship||'Parent')+'</div></div><small class="ac-balance">'+Number(parent.family_balance||0)+' credits available</small>';
            card.addEventListener('click',function(){state.parent=parent;parentList.querySelectorAll('.ac-parent-card').forEach(function(el){el.classList.remove('active')});card.classList.add('active')});
            parentList.appendChild(card);
        });
        if(!(state.child.parents||[]).length)parentList.innerHTML='<div class="alert alert-warning mb-0">No linked parent is available for this child.</div>';
    }

    childSearch.addEventListener('input',renderChildren);

    document.querySelectorAll('#acesAddCreditsModal .ac-credit-card').forEach(function(card){
        card.addEventListener('click',function(){
            document.querySelectorAll('#acesAddCreditsModal .ac-credit-card').forEach(function(el){el.classList.remove('active')});card.classList.add('active');
            state.creditOption=card.dataset.acCredit;customWrap.style.display=state.creditOption==='custom'?'block':'none';
            state.credits=state.creditOption==='custom'?Number(customCredits.value||0):Number(state.creditOption);
        });
    });
    customCredits.addEventListener('input',function(){if(state.creditOption==='custom')state.credits=Number(customCredits.value||0)});

    function validateStep(){
        if(step===1&&!state.child){showError('Select an active child to continue.');return false}
        if(step===2&&!state.parent){showError('Select Parent 1 or Parent 2 to continue.');return false}
        if(step===3){state.note=note.value.trim();state.credits=state.creditOption==='custom'?Number(customCredits.value||0):Number(state.creditOption||0);if(!state.creditOption||state.credits<1){showError('Select 1, 6, 12, or enter a custom credit amount.');return false}}
        return true;
    }

    function renderReview(){
        state.note=note.value.trim();var current=Number(state.parent?.family_balance||0);
        document.getElementById('acReviewChild').textContent=state.child?.name||'—';
        document.getElementById('acReviewParent').textContent=(state.parent?.relationship?state.parent.relationship+': ':'')+(state.parent?.name||'—');
        document.getElementById('acReviewCredits').textContent=String(state.credits||0);
        document.getElementById('acReviewCurrent').textContent=String(current);
        document.getElementById('acReviewNew').textContent=String(current+Number(state.credits||0));
        document.getElementById('acReviewNote').textContent=state.note||'—';
    }

    nextBtn.addEventListener('click',function(){clearError();if(!validateStep())return;if(step===1)renderParents();if(step<4)setStep(step+1)});
    backBtn.addEventListener('click',function(){if(step>1)setStep(step-1)});

    confirmBtn.addEventListener('click',async function(){
        clearError();confirmBtn.disabled=true;var original=confirmBtn.innerHTML;confirmBtn.innerHTML='<i class="fa-solid fa-circle-notch fa-spin me-2"></i>Saving...';
        try{
            var response=await fetch(storeUrl,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':csrf},body:JSON.stringify({child_id:state.child.id,customer_id:state.parent.id,credit_option:state.creditOption,custom_credits:state.creditOption==='custom'?state.credits:null,note:state.note||null})});
            var data=await response.json();if(!response.ok||!data.status)throw new Error(data.message||Object.values(data.errors||{})[0]?.[0]||'Could not add credits.');
            bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            var balance=data.family_balance_after??data.credit_balance_after;
            if(window.Swal){await Swal.fire({icon:'success',title:'Credits Added',text:data.message+(balance!==undefined?' New credit balance: '+balance+'.':''),confirmButtonColor:'#611eb2'})}else alert(data.message);
            window.location.reload();
        }catch(error){showError(error.message||'Could not add credits.')}
        finally{confirmBtn.disabled=false;confirmBtn.innerHTML=original}
    });

    modalEl.addEventListener('show.bs.modal',loadOptions);
});
</script>