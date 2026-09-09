<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EMCustomerCredit;
use App\Models\EMPackageOrder;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $search=trim((string)$request->get('search'));$method=trim((string)$request->get('payment_method'));$date=$request->get('payment_date');
        $query=EMPackageOrder::with([
            'customer','items','paymentLogs'=>fn($q)=>$q->orderByDesc('id'),
            'bookings.child','bookings.sessionEvent'
        ])->where(function($q){$q->whereIn('payment_status',['paid','success','completed'])->orWhereNotNull('paid_at');});
        if($search!=='')$query->where(function($q)use($search){$q->where('order_no','like','%'.$search.'%')->orWhereHas('customer',fn($c)=>$c->where('first_name','like','%'.$search.'%')->orWhere('last_name','like','%'.$search.'%')->orWhere('email','like','%'.$search.'%')->orWhere('phone','like','%'.$search.'%'))->orWhereHas('paymentLogs',fn($p)=>$p->where('transaction_id','like','%'.$search.'%')->orWhere('billing_email','like','%'.$search.'%')->orWhere('billing_phone','like','%'.$search.'%'));});
        if($method!=='')$query->where(function($q)use($method){$q->where('payment_method',$method)->orWhereHas('paymentLogs',fn($p)=>$p->where('payment_method',$method));});
        if($date)$query->where(function($q)use($date){$q->whereDate('paid_at',$date)->orWhereHas('paymentLogs',fn($p)=>$p->whereDate('paid_at',$date));});
        $payments=$query->orderByDesc('paid_at')->orderByDesc('id')->paginate((int)$request->get('per_page',25)?:25)->withQueryString();
        $customerIds=$payments->getCollection()->pluck('customer_id')->filter()->unique();
        $credits=EMCustomerCredit::whereIn('customer_id',$customerIds)->available()->get()->groupBy('customer_id');
        $payments->getCollection()->transform(function($order)use($credits){$order->admin_payment_log=$order->paymentLogs->first();$order->admin_available_credits=(int)$credits->get($order->customer_id,collect())->sum('remaining_classes');return $order;});
        return view('admin.payments.index',compact('payments','search','method','date'));
    }
}
