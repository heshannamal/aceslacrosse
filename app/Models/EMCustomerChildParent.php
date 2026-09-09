<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EMCustomerChildParent extends Model
{
    protected $table='em_customer_child_parents';
    protected $guarded=[];
    protected $casts=['is_primary'=>'boolean','can_book'=>'boolean','can_pay'=>'boolean','can_pickup'=>'boolean'];
    public function customer(){ return $this->belongsTo(EMCustomer::class,'customer_id'); }
    public function child(){ return $this->belongsTo(EMCustomerChild::class,'child_id'); }
}
