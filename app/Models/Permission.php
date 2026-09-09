<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Permission extends Model
{
    protected $fillable = ['name','slug','description','active'];
    protected $casts = ['active'=>'boolean'];
    public function userGroups(){ return $this->belongsToMany(UserGroup::class,'permission_user_group')->withTimestamps(); }
    public function scopeActive($query){ return $query->where('active',1); }
}
