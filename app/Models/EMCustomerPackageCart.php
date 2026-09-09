<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EMCustomerPackageCart extends Model
{
    protected $table='em_customer_package_carts'; protected $guarded=[];
    protected $casts=['unit_price'=>'decimal:2','total_price'=>'decimal:2'];
    public function customer(){return $this->belongsTo(EMCustomer::class,'customer_id');}
    public function package(){return $this->belongsTo(EMPackage::class,'package_id');}
    public function booking(){return $this->belongsTo(EMSessionBooking::class,'booking_id');}
}
