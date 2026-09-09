<?php

namespace App\Http\Controllers;

use App\Models\EMCustomer;
use App\Models\EMCustomerChild;
use App\Models\EMCustomerChildParent;
use App\Models\EMCustomerCredit;
use App\Models\EMCustomerCreditLog;
use App\Models\EMCustomerPackageCart;
use App\Models\EMPackage;
use App\Models\EMPackageOrder;
use App\Models\EMPackageOrderItem;
use App\Models\EMPackagePaymentLog;
use App\Models\EMSessionBooking;
use App\Models\EMSessionEvent;
use App\Services\Training\AuthorizeNetService;
use App\Services\Training\TrainingMailService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use RuntimeException;

class EMCustomerController extends Controller
{
    // pending_payment is a cart item, not a booked session. Only confirmed
    // bookings consume capacity and participate in normal booking validation.
    private const ACTIVE_BOOKING_STATUSES = ['booked', 'paid', 'completed'];
    private const TIMEZONE = 'America/Los_Angeles';
    private const PROCESSING_FEE_RATE = 0.03;

    public function login()
    {
        if ($this->customer(false)) return redirect()->route('em.customer.index');
        return view('pages.customer_sessions.auth', ['mode' => 'login']);
    }

    public function loginSubmit(Request $request)
    {
        $data = $request->validate(['email' => ['required','email','max:190'], 'password' => ['required','string']]);
        $customer = EMCustomer::where('email', strtolower(trim($data['email'])))->where('active',1)->first();
        if (!$customer || !$customer->password || !Hash::check($data['password'], $customer->password)) {
            return back()->withInput($request->only('email'))->withErrors(['email' => 'The email address or password is incorrect.']);
        }
        $this->loginCustomer($request, $customer);
        return redirect()->intended(route('em.customer.index'));
    }

    public function register()
    {
        if ($this->customer(false)) return redirect()->route('em.customer.index');
        return view('pages.customer_sessions.auth', ['mode' => 'register']);
    }

    public function registerSubmit(Request $request)
    {
        $data = $request->validate([
            'first_name'=>['required','string','max:100'], 'last_name'=>['nullable','string','max:100'],
            'email'=>['required','email','max:190',Rule::unique('em_customers','email')], 'phone'=>['nullable','string','max:80'],
            'password'=>['required','string','min:8','confirmed'],
        ]);
        $customer = EMCustomer::create([
            'first_name'=>trim($data['first_name']), 'last_name'=>trim((string)($data['last_name']??''))?:null,
            'email'=>strtolower(trim($data['email'])), 'phone'=>trim((string)($data['phone']??''))?:null,
            'password'=>Hash::make($data['password']), 'parent_type'=>1, 'active'=>1,
        ]);
        $this->loginCustomer($request, $customer);
        return redirect()->route('em.customer.index')->with('success','Welcome to Alcatraz Outlaws Training.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['em_customer_id','em_customer_name']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('em.customer.login')->with('success','You have been logged out.');
    }

    public function googleRedirect()
    {
        $clientId=trim((string)config('services.google.client_id')); $redirect=trim((string)config('services.google.redirect'));
        if ($clientId==='' || $redirect==='') return redirect()->route('em.customer.login')->with('error','Google sign-in is not configured yet.');
        $state=Str::random(40); session(['em_google_oauth_state'=>$state]);
        $query=http_build_query(['client_id'=>$clientId,'redirect_uri'=>$redirect,'response_type'=>'code','scope'=>'openid email profile','access_type'=>'online','prompt'=>'select_account','state'=>$state]);
        return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?'.$query);
    }

    public function googleCallback(Request $request)
    {
        if (!$request->filled('code') || !$request->filled('state') || !hash_equals((string)session('em_google_oauth_state'),(string)$request->state)) {
            return redirect()->route('em.customer.login')->with('error','Google sign-in could not be verified.');
        }
        session()->forget('em_google_oauth_state');
        try {
            $tokenResponse=Http::asForm()->timeout(20)->post('https://oauth2.googleapis.com/token',[
                'code'=>$request->code,'client_id'=>config('services.google.client_id'),'client_secret'=>config('services.google.client_secret'),
                'redirect_uri'=>config('services.google.redirect'),'grant_type'=>'authorization_code',
            ]);
            if (!$tokenResponse->successful() || !$tokenResponse->json('access_token')) throw new RuntimeException('Unable to exchange the Google authorization code.');
            $profileResponse=Http::withToken($tokenResponse->json('access_token'))->timeout(20)->get('https://www.googleapis.com/oauth2/v2/userinfo');
            if (!$profileResponse->successful() || !$profileResponse->json('email')) throw new RuntimeException('Unable to retrieve your Google profile.');
            $profile=$profileResponse->json(); $email=strtolower(trim((string)$profile['email'])); $customer=EMCustomer::where('email',$email)->first();
            if ($customer && !$customer->active) return redirect()->route('em.customer.login')->with('error','This customer account is inactive.');
            if (!$customer) {
                $customer=EMCustomer::create(['first_name'=>trim((string)($profile['given_name']??$profile['name']??'Parent')),'last_name'=>trim((string)($profile['family_name']??''))?:null,'email'=>$email,'google_id'=>(string)($profile['id']??''),'profile_photo'=>(string)($profile['picture']??''),'email_verified_at'=>now(),'parent_type'=>1,'active'=>1]);
            } else {
                $customer->google_id=(string)($profile['id']??$customer->google_id);
                if (!$customer->profile_photo && !empty($profile['picture'])) $customer->profile_photo=(string)$profile['picture'];
                if (!$customer->email_verified_at) $customer->email_verified_at=now();
                $customer->save();
            }
            $this->loginCustomer($request,$customer);
            return redirect()->intended(route('em.customer.index'));
        } catch (\Throwable $e) {
            report($e); return redirect()->route('em.customer.login')->with('error','Google sign-in failed. Please try email and password instead.');
        }
    }

    public function forgotPassword(){ return view('pages.customer_sessions.forgot-password'); }

    public function forgotPasswordSubmit(Request $request, TrainingMailService $mail)
    {
        $request->validate(['email'=>['required','email','max:190']]); $email=strtolower(trim((string)$request->email));
        $customer=EMCustomer::where('email',$email)->where('active',1)->first();
        if ($customer) {
            $cooldownUntil=$customer->password_reset_requested_at ? $customer->password_reset_requested_at->copy()->addMinutes(10) : null;
            if (!$cooldownUntil || now()->gte($cooldownUntil)) {
                $plain=Str::random(64); $customer->password_reset_token=hash('sha256',$plain); $customer->password_reset_token_encrypted=Crypt::encryptString($plain);
                $customer->password_reset_expires_at=now()->addHour(); $customer->password_reset_requested_at=now(); $customer->save();
                $mail->passwordReset($customer,route('em.customer.password.reset',['token'=>$plain,'email'=>$customer->email]));
            }
        }
        return back()->with('success','If that email belongs to an active Training account, a password-reset link has been sent.');
    }

    public function resetPassword(Request $request,string $token){ return view('pages.customer_sessions.reset-password',['token'=>$token,'email'=>(string)$request->query('email','')]); }

    public function resetPasswordSubmit(Request $request)
    {
        $data=$request->validate(['token'=>['required','string'],'email'=>['required','email','max:190'],'password'=>['required','string','min:8','confirmed']]);
        try {
            DB::transaction(function() use($data){
                $customer=EMCustomer::where('email',strtolower(trim($data['email'])))->where('active',1)->lockForUpdate()->first();
                if (!$customer || !$customer->password_reset_token || !hash_equals((string)$customer->password_reset_token,hash('sha256',$data['token'])) || !$customer->password_reset_expires_at || $customer->password_reset_expires_at->isPast()) throw new RuntimeException('This password-reset link is invalid or has expired.');
                $customer->password=Hash::make($data['password']); $customer->password_reset_token=null; $customer->password_reset_token_encrypted=null; $customer->password_reset_expires_at=null; $customer->password_reset_requested_at=null; $customer->save();
            });
        } catch (\Throwable $e) { return back()->withInput($request->only('email','token'))->withErrors(['email'=>$e->getMessage()]); }
        return redirect()->route('em.customer.login')->with('success','Your password has been reset. You can now log in.');
    }

    public function landingIndex()
    {
        $customer=$this->customer(); $sessions=$this->upcomingSessions(); $packages=$this->activePackages()->take(3);
        $children=$customer->activeChildren()->orderBy('first_name')->orderBy('last_name')->get(); $summary=$this->customerSummary($customer);
        $cartCount=EMCustomerPackageCart::where('customer_id',$customer->id)->sum('quantity');
        return view('pages.customer_sessions.landing.index',compact('customer','sessions','packages','children','summary','cartCount'));
    }

    public function calendarEvents()
    {
        return response()->json($this->upcomingSessions()->map(fn(EMSessionEvent $s)=>['id'=>$s->id,'title'=>$s->training_type?:$s->name,'date'=>$s->event_date?->format('Y-m-d'),'start'=>$s->start_time,'end'=>$s->end_time,'location'=>$s->location,'instructor'=>$s->instructor,'capacity'=>(int)$s->capacity,'booked'=>(int)$s->bookings_count,'spots_left'=>$s->spots_left,'full'=>(bool)$s->is_full]));
    }

    public function packages(){ return view('pages.customer_sessions.packages',['customer'=>$this->customer(),'packages'=>$this->activePackages()]); }
    public function packageDetails($id){ return view('pages.customer_sessions.package-details',['customer'=>$this->customer(),'package'=>$this->activePackageQuery()->findOrFail($id)]); }

    public function dashboard()
    {
        $customer=$this->customer(); $summary=$this->customerSummary($customer);
        $bookings=EMSessionBooking::with(['child','sessionEvent','package'])->where('customer_id',$customer->id)->orderByDesc('booked_at')->orderByDesc('id')->get();
        $orders=EMPackageOrder::with('items.package')->where('customer_id',$customer->id)->orderByDesc('id')->get();
        return view('pages.customer_sessions.dashboard',compact('customer','summary','bookings','orders'));
    }
    public function bookings(){ return $this->dashboard(); }

    public function bookSession(Request $request,$id,TrainingMailService $mail)
    {
        $customer=$this->customer(); $data=$request->validate(['child_id'=>['required','integer'],'package_id'=>['nullable','integer']]);
        $child=EMCustomerChild::where('id',$data['child_id'])->where('is_active',1)->firstOrFail(); $this->ensureChildOwnership($customer,$child);
        try {
            $result=DB::transaction(function() use($customer,$child,$id,$data){
                $session=EMSessionEvent::whereKey($id)->where('is_active',1)->lockForUpdate()->firstOrFail();

                // A customer's own pending checkout item should prevent adding the
                // same player/session to the cart twice, but it must not reserve a
                // capacity spot or make the session unavailable to other customers.
                $alreadyInCart=EMSessionBooking::query()
                    ->where('customer_id',$customer->id)
                    ->where('session_event_id',$session->id)
                    ->where('status','pending_payment')
                    ->whereNotNull('cart_id')
                    ->where(fn($q)=>$q->where('child_id',$child->id)->orWhere('customer_child_id',$child->id))
                    ->exists();
                if($alreadyInCart) throw new RuntimeException('This session is already in your cart.');

                $this->assertSessionBookable($session,$child->id);
                $credit=EMCustomerCredit::where('customer_id',$customer->id)->available()->orderByRaw('valid_until IS NULL, valid_until ASC')->orderBy('id')->lockForUpdate()->first();
                if ($credit) {
                    $booking=$this->createBooking($customer,$child,$session,['package_id'=>$credit->package_id,'credit_id'=>$credit->id,'status'=>'booked','booked_at'=>now()]);
                    $this->consumeCredit($credit,$booking,'Customer booked '.($session->training_type?:$session->name));
                    return ['booking'=>$booking,'redirect'=>'dashboard'];
                }
                $package=$this->packageForBooking($data['package_id']??null); if (!$package) throw new RuntimeException('No active training package is available. Please contact Alcatraz Outlaws.');
                $booking=$this->createBooking($customer,$child,$session,['package_id'=>$package->id,'status'=>'pending_payment']);
                $cart=EMCustomerPackageCart::create(['customer_id'=>$customer->id,'booking_id'=>$booking->id,'package_id'=>$package->id,'source_type'=>'booking','quantity'=>1,'unit_price'=>$package->package_price,'total_price'=>$package->package_price]);
                $booking->cart_id=$cart->id; $booking->save(); return ['booking'=>$booking,'redirect'=>'cart'];
            });
        } catch (\Throwable $e) { return back()->with('error',$e->getMessage()); }
        if ($result['redirect']==='dashboard') { $mail->bookingCreated($result['booking']); return redirect()->route('em.customer.dashboard')->with('success','Training session booked successfully.'); }
        return redirect()->route('em.customer.cart')->with('success','Session selected. Complete checkout to confirm the booking.');
    }

    public function addToCart(Request $request,$id)
    {
        $customer=$this->customer(); $package=$this->activePackageQuery()->findOrFail($id); $quantity=max(1,min(10,(int)$request->input('quantity',1)));
        $cart=EMCustomerPackageCart::firstOrNew(['customer_id'=>$customer->id,'package_id'=>$package->id,'source_type'=>'direct','booking_id'=>null]);
        $cart->quantity=min(10,($cart->exists?(int)$cart->quantity:0)+$quantity); $cart->unit_price=$package->package_price; $cart->total_price=round((float)$package->package_price*$cart->quantity,2); $cart->save();
        return redirect()->route('em.customer.cart')->with('success','Training package added to your cart.');
    }

    public function cart()
    {
        $customer=$this->customer(); $cartItems=EMCustomerPackageCart::with(['package','booking.child','booking.sessionEvent'])->where('customer_id',$customer->id)->orderBy('id')->get();
        $totals=$this->cartTotals($cartItems); $summary=$this->customerSummary($customer); return view('pages.customer_sessions.cart',compact('customer','cartItems','totals','summary'));
    }

    public function updateCart(Request $request,$id)
    {
        $customer=$this->customer(); $data=$request->validate(['quantity'=>['required','integer','min:1','max:10']]);
        $cart=EMCustomerPackageCart::with('package')->where('customer_id',$customer->id)->findOrFail($id); $cart->quantity=$data['quantity']; $cart->unit_price=$cart->package->package_price; $cart->total_price=round((float)$cart->unit_price*$cart->quantity,2); $cart->save();
        return back()->with('success','Cart updated.');
    }

    public function removeCart($id)
    {
        $customer=$this->customer(); DB::transaction(function() use($customer,$id){ $cart=EMCustomerPackageCart::where('customer_id',$customer->id)->lockForUpdate()->findOrFail($id); if($cart->booking_id) EMSessionBooking::where('id',$cart->booking_id)->where('customer_id',$customer->id)->where('status','pending_payment')->delete(); $cart->delete(); });
        return back()->with('success','Item removed from your training cart.');
    }

    public function checkout()
    {
        $customer=$this->customer(); $cartItems=EMCustomerPackageCart::with(['package','booking.child','booking.sessionEvent'])->where('customer_id',$customer->id)->orderBy('id')->get();
        if($cartItems->isEmpty()) return redirect()->route('em.customer.index')->with('error','Your training cart is empty.');
        return view('pages.customer_sessions.checkout',['customer'=>$customer,'cartItems'=>$cartItems,'totals'=>$this->cartTotals($cartItems),'summary'=>$this->customerSummary($customer)]);
    }

    public function pay(Request $request,AuthorizeNetService $gateway,TrainingMailService $mail)
    {
        $customer=$this->customer(); $data=$request->validate([
            'first_name'=>['required','string','max:100'],'last_name'=>['required','string','max:100'],'email'=>['required','email','max:190'],'phone'=>['nullable','string','max:50'],
            'street'=>['required','string','max:190'],'city'=>['required','string','max:100'],'state'=>['required','string','max:100'],'zip'=>['required','string','max:30'],'country'=>['required','string','max:100'],
            'name_on_card'=>['required','string','max:190'],'card_number'=>['required','string','regex:/^[0-9\s-]{12,24}$/'],'exp_month'=>['required','integer','between:1,12'],'exp_year'=>['required','integer','min:'.now()->year,'max:'.(now()->year+20)],'cvv'=>['required','string','regex:/^[0-9]{3,4}$/'],
        ]);
        $cartItems=EMCustomerPackageCart::with(['package','booking.sessionEvent'])->where('customer_id',$customer->id)->orderBy('id')->get(); if($cartItems->isEmpty()) return redirect()->route('em.customer.cart')->with('error','Your training cart is empty.');
        $totals=$this->cartTotals($cartItems); $orderNo='AO-TR-'.now()->format('Ymd').'-'.strtoupper(Str::random(6));
        try { $gatewayResult=$gateway->charge(['number'=>$data['card_number'],'month'=>$data['exp_month'],'year'=>$data['exp_year'],'cvv'=>$data['cvv']],$totals['total'],$data,$orderNo); }
        catch(\Throwable $e){ report($e); return back()->withInput($request->except(['card_number','cvv']))->with('error',$e->getMessage()); }
        $confirmedBookings=collect();
        try {
            $order=DB::transaction(function() use($customer,$cartItems,$totals,$data,$gatewayResult,$orderNo,&$confirmedBookings){
                $last4=substr(preg_replace('/\D+/','',$data['card_number']),-4);
                $order=EMPackageOrder::create(['customer_id'=>$customer->id,'order_no'=>$orderNo,'subtotal'=>$totals['subtotal'],'tax'=>$totals['tax'],'processing_fee'=>$totals['processing_fee'],'total'=>$totals['total'],'payment_status'=>'paid','payment_method'=>'card','card_last_four'=>$last4,'paid_at'=>now()]);
                EMPackagePaymentLog::create(['package_order_id'=>$order->id,'customer_id'=>$customer->id,'gateway'=>'authorize_net','environment'=>$gatewayResult['environment'],'payment_method'=>'card','status'=>'paid','subtotal'=>$totals['subtotal'],'processing_fee'=>$totals['processing_fee'],'tax'=>$totals['tax'],'amount'=>$totals['total'],'response_code'=>$gatewayResult['response_code'],'transaction_id'=>$gatewayResult['transaction_id'],'auth_id'=>$gatewayResult['auth_code'],'message_text'=>$gatewayResult['message'],'ref_id'=>$gatewayResult['ref_id'],'name_on_card'=>$data['name_on_card'],'card_last_four'=>$last4,'billing_first_name'=>$data['first_name'],'billing_last_name'=>$data['last_name'],'billing_email'=>$data['email'],'billing_phone'=>$data['phone']??null,'billing_street'=>$data['street'],'billing_city'=>$data['city'],'billing_state'=>$data['state'],'billing_zip'=>$data['zip'],'billing_country'=>$data['country'],'quantity'=>max(1,(int)$cartItems->sum('quantity')),'paid_at'=>now()]);
                foreach($cartItems as $cartRef){
                    $cart=EMCustomerPackageCart::with(['package','booking'])->where('customer_id',$customer->id)->lockForUpdate()->findOrFail($cartRef->id); $package=$cart->package; if(!$package) throw new RuntimeException('A package in your cart is no longer available.');
                    $orderItem=EMPackageOrderItem::create(['order_id'=>$order->id,'package_id'=>$package->id,'booking_id'=>$cart->booking_id,'package_name'=>$package->package_name,'quantity'=>$cart->quantity,'classes_per_package'=>$package->available_classes,'unit_price'=>$cart->unit_price,'total_price'=>$cart->total_price]);
                    $totalClasses=max(1,(int)$package->available_classes)*max(1,(int)$cart->quantity);
                    $credit=EMCustomerCredit::create(['customer_id'=>$customer->id,'package_id'=>$package->id,'order_id'=>$order->id,'total_classes'=>$totalClasses,'used_classes'=>0,'remaining_classes'=>$totalClasses,'valid_from'=>today(),'valid_until'=>null,'status'=>'active']);
                    if($cart->booking && $cart->booking->status==='pending_payment'){
                        $booking=EMSessionBooking::whereKey($cart->booking->id)->lockForUpdate()->first(); if($booking){ $session=EMSessionEvent::whereKey($booking->session_event_id)->lockForUpdate()->first(); if(!$session) throw new RuntimeException('The selected training session no longer exists.'); $this->assertSessionBookable($session,$booking->child_id?:$booking->customer_child_id,$booking->id); $booking->package_id=$package->id; $booking->credit_id=$credit->id; $booking->order_id=$order->id; $booking->order_item_id=$orderItem->id; $booking->status='booked'; $booking->booked_at=now(); $booking->save(); $this->consumeCredit($credit,$booking,'Training booking confirmed after payment.'); $confirmedBookings->push($booking); }
                    }
                    $cart->delete();
                }
                return $order;
            });
        } catch(\Throwable $e){ report($e); return redirect()->route('em.customer.cart')->with('error','Payment was approved, but the local order could not be finalized. Please contact Alcatraz Outlaws with transaction '.$gatewayResult['transaction_id'].'.'); }
        $mail->receipt($customer,$order); $confirmedBookings->each(fn($booking)=>$mail->bookingCreated($booking));
        return redirect()->route('em.customer.payment.success',$order->id)->with('success','Payment completed successfully.');
    }

    public function paymentSuccess($orderId)
    {
        $customer=$this->customer(); $order=EMPackageOrder::with(['items.package','paymentLogs'])->where('customer_id',$customer->id)->findOrFail($orderId);
        return view('pages.customer_sessions.payment-success',compact('customer','order'));
    }

    public function cancelBooking($id,TrainingMailService $mail)
    {
        $customer=$this->customer(); $creditReturned=false;
        $booking=DB::transaction(function() use($customer,$id,&$creditReturned){
            $booking=EMSessionBooking::with(['child','sessionEvent'])->where('customer_id',$customer->id)->lockForUpdate()->findOrFail($id); if(in_array($booking->status,['cancelled','refunded'],true)) return $booking;
            if($booking->credit_id){ $credit=EMCustomerCredit::whereKey($booking->credit_id)->lockForUpdate()->first(); if($credit){ $credit->used_classes=max(0,(int)$credit->used_classes-1); $credit->remaining_classes=(int)$credit->remaining_classes+1; $credit->status='active'; $credit->save(); $creditReturned=true; EMCustomerCreditLog::create(['customer_id'=>$customer->id,'credit_id'=>$credit->id,'booking_id'=>$booking->id,'type'=>'refund','classes'=>1,'note'=>'Customer cancelled booking and received 1 class credit.','description'=>'Training booking cancellation credit return.']); } }
            if($booking->cart_id) EMCustomerPackageCart::whereKey($booking->cart_id)->delete(); $booking->status='cancelled'; $booking->cancelled_at=now(); $booking->save(); return $booking;
        });
        $mail->bookingCancelled($booking,$booking->sessionEvent,$creditReturned); return back()->with('success','Booking cancelled'.($creditReturned?' and one class credit was returned.':'.'));
    }

    public function profile(){ $customer=$this->customer(); $children=$customer->activeChildren()->orderBy('first_name')->orderBy('last_name')->get(); return view('pages.customer_sessions.profile',compact('customer','children')); }

    public function updateProfile(Request $request)
    {
        $customer=$this->customer(); $data=$request->validate([
            'first_name'=>['required','string','max:100'],'last_name'=>['nullable','string','max:100'],'email'=>['required','email','max:190',Rule::unique('em_customers','email')->ignore($customer->id)],'phone'=>['nullable','string','max:80'],
            'profile_photo'=>['nullable','image','max:5120'],'current_password'=>['nullable','required_with:password','string'],'password'=>['nullable','string','min:8','confirmed'],
        ]);
        if(!empty($data['password'])){ if(!$customer->password || !Hash::check((string)$data['current_password'],$customer->password)) return back()->withErrors(['current_password'=>'Your current password is incorrect.']); $customer->password=Hash::make($data['password']); }
        if($request->hasFile('profile_photo')){ if($customer->profile_photo && !Str::startsWith($customer->profile_photo,['http://','https://'])) Storage::disk('public')->delete($customer->profile_photo); $customer->profile_photo=$request->file('profile_photo')->store('training-customers','public'); }
        $customer->first_name=trim($data['first_name']); $customer->last_name=trim((string)($data['last_name']??''))?:null; $customer->email=strtolower(trim($data['email'])); $customer->phone=trim((string)($data['phone']??''))?:null; $customer->save(); session(['em_customer_name'=>$customer->display_name]); return back()->with('success','Training profile updated.');
    }

    public function storeChild(Request $request)
    {
        $customer=$this->customer(); $data=$this->childValidation($request);
        DB::transaction(function() use($customer,$data){ $child=EMCustomerChild::create($data+['is_active'=>1]); EMCustomerChildParent::create(['customer_id'=>$customer->id,'child_id'=>$child->id,'relationship'=>'Parent 1','is_primary'=>1,'can_book'=>1,'can_pay'=>1,'can_pickup'=>0]); });
        return back()->with('success','Player added.');
    }

    public function updateChild(Request $request,EMCustomerChild $child){ $customer=$this->customer(); $this->ensureChildOwnership($customer,$child); $child->update($this->childValidation($request)); return back()->with('success','Player updated.'); }

    public function deleteChild(EMCustomerChild $child)
    {
        $customer=$this->customer(); $this->ensureChildOwnership($customer,$child);
        // Pending cart items are not bookings for availability, but keeping them
        // here prevents removing a player while that player's cart item exists.
        $hasUpcoming=EMSessionBooking::where('customer_id',$customer->id)->where(fn($q)=>$q->where('child_id',$child->id)->orWhere('customer_child_id',$child->id))->whereIn('status',array_merge(self::ACTIVE_BOOKING_STATUSES,['pending_payment']))->whereHas('sessionEvent',fn($q)=>$q->whereDate('event_date','>=',today(self::TIMEZONE)))->exists();
        if($hasUpcoming) return back()->with('error','This player has an active upcoming booking or training cart item and cannot be removed yet.'); $child->is_active=0; $child->save(); return back()->with('success','Player removed.');
    }

    public function landingPackages(){ return $this->packages(); } public function landingPackageDetails($id){ return $this->packageDetails($id); } public function landingAddToCart(Request $r,$id){ return $this->addToCart($r,$id); } public function landingCart(){ return $this->cart(); } public function landingUpdateCart(Request $r,$id){ return $this->updateCart($r,$id); } public function landingRemoveCart($id){ return $this->removeCart($id); } public function landingCheckout(){ return $this->checkout(); } public function landingPay(Request $r,AuthorizeNetService $g,TrainingMailService $m){ return $this->pay($r,$g,$m); } public function landingPaymentSuccess($id){ return $this->paymentSuccess($id); }

    private function loginCustomer(Request $request,EMCustomer $customer):void{ $request->session()->regenerate(); $request->session()->put('em_customer_id',$customer->id); $request->session()->put('em_customer_name',$customer->display_name); }
    private function customer(bool $fail=true):?EMCustomer{ $id=session('em_customer_id'); $customer=$id?EMCustomer::whereKey($id)->where('active',1)->first():null; if(!$customer&&$fail) abort(401,'Training customer login required.'); return $customer; }

    private function activePackageQuery(){ return EMPackage::query()->where(fn($q)=>$q->whereNull('is_active')->orWhere('is_active',1))->where(fn($q)=>$q->whereNull('active')->orWhere('active',1))->where(fn($q)=>$q->whereNull('status')->orWhere('status','active')); }
    private function activePackages(){ return $this->activePackageQuery()->orderBy('available_classes')->orderBy('package_price')->get(); }
    private function packageForBooking($requestedId):?EMPackage{ if($requestedId){ $p=$this->activePackageQuery()->whereKey($requestedId)->first(); if($p)return $p; } return $this->activePackageQuery()->where('available_classes','>=',1)->orderBy('package_price')->orderBy('available_classes')->first(); }

    private function upcomingSessions()
    {
        $sessions=EMSessionEvent::where('is_active',1)->orderBy('event_date')->orderBy('start_time')->get(); $ids=$sessions->pluck('id');
        $counts=EMSessionBooking::whereIn('session_event_id',$ids)->whereIn('status',self::ACTIVE_BOOKING_STATUSES)->selectRaw('session_event_id, COUNT(*) total')->groupBy('session_event_id')->pluck('total','session_event_id'); $now=now(self::TIMEZONE);
        return $sessions->map(function($s)use($counts){ $s->bookings_count=(int)($counts[$s->id]??0); $s->spots_left=(int)$s->capacity>0?max(0,(int)$s->capacity-$s->bookings_count):null; $s->is_full=(int)$s->capacity>0&&$s->bookings_count>=(int)$s->capacity; $s->ends_at=$this->sessionEnd($s); return $s; })->filter(fn($s)=>$s->ends_at&&$s->ends_at->gte($now))->values();
    }

    private function sessionEnd(EMSessionEvent $session):?Carbon
    {
        try{ $date=$session->event_date instanceof Carbon?$session->event_date->format('Y-m-d'):Carbon::parse($session->event_date)->format('Y-m-d'); $time=Carbon::parse($session->end_time?:($session->start_time?:'23:59:59'))->format('H:i:s'); return Carbon::parse($date.' '.$time,self::TIMEZONE); }catch(\Throwable $e){ return null; }
    }

    private function assertSessionBookable(EMSessionEvent $session,$childId,?int $ignore=null):void
    {
        $end=$this->sessionEnd($session); if(!$end||$end->lt(now(self::TIMEZONE))) throw new RuntimeException('This training session has already ended.');
        $active=EMSessionBooking::where('session_event_id',$session->id)->whereIn('status',self::ACTIVE_BOOKING_STATUSES); if($ignore)$active->where('id','!=',$ignore); if((int)$session->capacity>0&&$active->count()>=(int)$session->capacity) throw new RuntimeException('This training session is full.');
        $dup=EMSessionBooking::where('session_event_id',$session->id)->whereIn('status',self::ACTIVE_BOOKING_STATUSES)->where(fn($q)=>$q->where('child_id',$childId)->orWhere('customer_child_id',$childId)); if($ignore)$dup->where('id','!=',$ignore); if($dup->exists()) throw new RuntimeException('This player is already booked for this session.');
    }

    private function createBooking(EMCustomer $c,EMCustomerChild $child,EMSessionEvent $s,array $extra):EMSessionBooking
    {
        return EMSessionBooking::create(array_merge(['customer_id'=>$c->id,'child_id'=>$child->id,'customer_child_id'=>$child->id,'session_event_id'=>$s->id,'booking_no'=>'BKG-'.now()->format('Ymd').'-'.strtoupper(Str::random(6)),'player_first'=>$child->first_name,'player_last'=>$child->last_name,'grad_year'=>is_numeric($child->class_year)?(int)$child->class_year:null,'positions'=>$child->position],$extra));
    }

    private function consumeCredit(EMCustomerCredit $credit,EMSessionBooking $booking,string $note):void
    {
        if((int)$credit->remaining_classes<=0) throw new RuntimeException('No class credits remain on this package.'); $credit->remaining_classes=(int)$credit->remaining_classes-1; $credit->used_classes=(int)$credit->used_classes+1; if((int)$credit->remaining_classes<=0)$credit->status='used'; $credit->save();
        EMCustomerCreditLog::create(['customer_id'=>$booking->customer_id,'credit_id'=>$credit->id,'booking_id'=>$booking->id,'type'=>'used','classes'=>1,'note'=>$note,'description'=>'One Alcatraz Outlaws training class credit used.']);
    }

    private function customerSummary(EMCustomer $c):array
    {
        $credits=EMCustomerCredit::where('customer_id',$c->id)->get(); return ['total_credits'=>(int)$credits->sum('total_classes'),'used_credits'=>(int)$credits->sum('used_classes'),'remaining_credits'=>(int)$credits->sum('remaining_classes'),'booked_sessions'=>EMSessionBooking::where('customer_id',$c->id)->whereIn('status',['booked','paid','completed'])->count()];
    }
    private function cartTotals($items):array{ $subtotal=round((float)$items->sum(fn($i)=>(float)$i->total_price),2); $fee=round($subtotal*self::PROCESSING_FEE_RATE,2); return ['subtotal'=>$subtotal,'processing_fee'=>$fee,'tax'=>0.00,'total'=>round($subtotal+$fee,2)]; }
    private function ensureChildOwnership(EMCustomer $c,EMCustomerChild $child):void{ if(!EMCustomerChildParent::where('customer_id',$c->id)->where('child_id',$child->id)->exists()) abort(403,'This player is not linked to your account.'); }
    private function childValidation(Request $request):array
    {
        $data=$request->validate(['first_name'=>['required','string','max:100'],'last_name'=>['nullable','string','max:100'],'team'=>['nullable','string','max:150'],'spring_team'=>['nullable','string','max:150'],'position'=>['nullable','string','max:255'],'class_year'=>['nullable','string','max:20'],'birthdate'=>['nullable','date'],'gender'=>['nullable','string','max:30'],'school'=>['nullable','string','max:150'],'grade'=>['nullable','string','max:50'],'medical_notes'=>['nullable','string'],'allergies'=>['nullable','string']]);
        foreach(['first_name','last_name','team','spring_team','position','class_year','gender','school','grade'] as $key) if(array_key_exists($key,$data)&&is_string($data[$key])) $data[$key]=trim($data[$key])?:null; return $data;
    }
}
