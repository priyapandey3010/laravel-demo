<?php

namespace App\Modules\AuditTrail;

use App\Modules\Cases\Cases;
use App\Traits\Scopes;
use App\Modules\Role\Role;
use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model 
{
    use Scopes; 
    protected $table = 'audit_trail';

    protected $fillable = [
        'module_name',
        'activity_type',
        'user_name',
        'email_id',
        'role_id',
        'last_access',
        'last_login',
        'activity_datetime',
        'logout_time',
        'ip_address',
    ];
  
  public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
  public function scopeOrLikeEmail($query, $search)
    {
        return $query->orWhere('email_id', 'like', '%'. $search .'%');
    }

    public function scopeLikeUserName($query, $search)
    {
        return $query->orWhere('user_name', 'like', '%'. $search .'%');
    }

    public function scopeOrLikeRoleName($query, $search)
    {
        return $query->orWhere(function ($query) use ($search) {
            $query->whereHas('role', function ($query) use ($search) {
                $query->where('name', 'like', '%'. $search .'%');
            });
        });
    }
    
}