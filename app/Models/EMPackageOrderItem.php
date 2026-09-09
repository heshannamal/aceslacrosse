<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EMPackageOrderItem extends Model
{
    protected $table='em_package_order_items'; protected $guarded=[];
    protected $casts=['unit_price'=>'decimal:2','total_price'=>'decimal:2'];
    public function order(){return $this->belongsTo(EMPackageOrder::class,'order_id');}
    public function package(){return $this->belongsTo(EMPackage::class,'package_id');}
    public function booking(){return $this->belongsTo(EMSessionBooking::class,'booking_id');}
}
