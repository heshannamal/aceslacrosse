<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EMPackageOrder extends Model
{
    protected $table='em_package_orders'; protected $guarded=[];
    protected $casts=['subtotal'=>'decimal:2','tax'=>'decimal:2','processing_fee'=>'decimal:2','total'=>'decimal:2','paid_at'=>'datetime'];
    public function customer(){return $this->belongsTo(EMCustomer::class,'customer_id');}
    public function items(){return $this->hasMany(EMPackageOrderItem::class,'order_id');}
    public function paymentLogs(){return $this->hasMany(EMPackagePaymentLog::class,'package_order_id');}
    public function bookings(){return $this->hasMany(EMSessionBooking::class,'order_id');}
}
