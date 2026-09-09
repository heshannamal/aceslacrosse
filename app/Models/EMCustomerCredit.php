<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EMCustomerCredit extends Model
{
    protected $table='em_customer_credits';
    protected $guarded=[];
    protected $casts=['valid_from'=>'date','valid_until'=>'date'];
    public function customer(){ return $this->belongsTo(EMCustomer::class,'customer_id'); }
    public function package(){ return $this->belongsTo(EMPackage::class,'package_id'); }
    public function order(){ return $this->belongsTo(EMPackageOrder::class,'order_id'); }
    public function logs(){ return $this->hasMany(EMCustomerCreditLog::class,'credit_id'); }
    public function bookings(){ return $this->hasMany(EMSessionBooking::class,'credit_id'); }
    public function scopeAvailable($query){ return $query->where('status','active')->where('remaining_classes','>',0)->where(function($q){$q->whereNull('valid_from')->orWhereDate('valid_from','<=',today());})->where(function($q){$q->whereNull('valid_until')->orWhereDate('valid_until','>=',today());}); }
}
