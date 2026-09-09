<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EMSessionBooking extends Model
{
    protected $table='em_session_bookings';
    protected $guarded=[];
    protected $casts=['booked_at'=>'datetime','reminder_email_sent_at'=>'datetime','cancelled_at'=>'datetime','completed_at'=>'datetime'];
    public function customer(){ return $this->belongsTo(EMCustomer::class,'customer_id'); }
    public function child(){ return $this->belongsTo(EMCustomerChild::class,'child_id'); }
    public function sessionEvent(){ return $this->belongsTo(EMSessionEvent::class,'session_event_id'); }
    public function package(){ return $this->belongsTo(EMPackage::class,'package_id'); }
    public function credit(){ return $this->belongsTo(EMCustomerCredit::class,'credit_id'); }
    public function order(){ return $this->belongsTo(EMPackageOrder::class,'order_id'); }
}
