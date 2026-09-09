<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EMCustomerCreditLog extends Model
{
    protected $table='em_customer_credit_logs'; protected $guarded=[];
    public function customer(){return $this->belongsTo(EMCustomer::class,'customer_id');}
    public function credit(){return $this->belongsTo(EMCustomerCredit::class,'credit_id');}
    public function booking(){return $this->belongsTo(EMSessionBooking::class,'booking_id');}
}
