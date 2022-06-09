<?php

namespace App\Modules\UserPermission;

use App\Traits\Scopes;
use App\Traits\Mutators;
use App\Modules\UserPermission\UserPermission;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model 
{
    use Scopes, Mutators;

    protected $table = 'user_permissions';

    protected $fillable = [
        'user_id',
        'permission_id',
        'is_default',
        'group_permission_id'
    ];

    public function scopeUserId($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}