<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EMPackagePaymentLog extends Model
{
    protected $table='em_package_payment_logs'; protected $guarded=[];
    protected $casts=['subtotal'=>'decimal:2','processing_fee'=>'decimal:2','tax'=>'decimal:2','amount'=>'decimal:2','paid_at'=>'datetime'];
    public function packageOrder(){return $this->belongsTo(EMPackageOrder::class,'package_order_id');}
    public function customer(){return $this->belongsTo(EMCustomer::class,'customer_id');}
}
