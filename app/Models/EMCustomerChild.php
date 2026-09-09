<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EMCustomerChild extends Model
{
    protected $table='em_customer_children';
    protected $guarded=[];
    protected $casts=['birthdate'=>'date','is_active'=>'boolean'];
    public function parentRelations(){ return $this->hasMany(EMCustomerChildParent::class,'child_id'); }
    public function parents(){ return $this->belongsToMany(EMCustomer::class,'em_customer_child_parents','child_id','customer_id')->withPivot(['relationship','is_primary','can_book','can_pay','can_pickup','notes'])->withTimestamps(); }
    public function bookings(){ return $this->hasMany(EMSessionBooking::class,'child_id'); }
    public function getFullNameAttribute(){ return trim(($this->first_name ?? '').' '.($this->last_name ?? '')); }
}
