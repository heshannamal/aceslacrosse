@php
    $shopContextActive = !empty(session('encore_user_token')) || !empty(session('auth_api_token'));
    $trainingContextActive = !empty(session('em_customer_id'));
@endphp
@if($shopContextActive && !$trainingContextActive)
<form id="aoSwitchToTrainingForm" method="POST" action="{{ route('customer.module.switch.training') }}" hidden>
    @csrf
    <input type="hidden" name="redirect" value="{{ route('em.customer.index') }}">
</form>
<script>
document.addEventListener('DOMContentLoaded',function(){
    document.addEventListener('click',function(event){
        var target=event.target.closest('a[href*="/training/login"],button[data-training-login]');
        if(!target)return;
        event.preventDefault();
        var form=document.getElementById('aoSwitchToTrainingForm');
        if(!form)return;
        var message='You are now on Shop. If you continue, Shop will be signed out. Your Shop cart will stay saved and you can purchase those items after signing back in to Shop.';
        if(window.Swal){
            Swal.fire({icon:'warning',title:'Switch to Training?',text:message,showCancelButton:true,confirmButtonText:'Continue',cancelButtonText:'Stay in Shop',confirmButtonColor:'#f3282c',cancelButtonColor:'#111'}).then(function(result){if(result.isConfirmed)form.submit();});
        }else if(window.confirm(message)){form.submit();}
    });
});
</script>
@endif
