<?php

namespace App\Modules\RolePermission;

use App\Traits\Scopes;
use App\Traits\Mutators;
use App\Modules\RolePermission\RolePermission;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model 
{
    use Scopes, Mutators;

    protected $table = 'role_permissions';

    protected $fillable = [
        'role_id',
        'permission_id',
        'group_permission_id'
    ];

    public function scopeWhereRoleId($query, $roleId)
    {
        return $query->where('role_id', $roleId);
    }
}