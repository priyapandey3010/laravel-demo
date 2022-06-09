<?php

namespace App\Modules\AuditTrail;

use App\Modules\Cases\Cases;
use App\Traits\Scopes;
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
  
    
}