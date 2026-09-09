<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class UserGroup extends Model
{
    protected $fillable = ['name','slug','description','active'];
    protected $casts = ['active'=>'boolean'];
    public function permissions(){ return $this->belongsToMany(Permission::class,'permission_user_group')->withTimestamps(); }
    public function users(){ return $this->belongsToMany(User::class,'user_group_user')->withTimestamps(); }
    public function scopeActive($query){ return $query->where('active',1); }
}
