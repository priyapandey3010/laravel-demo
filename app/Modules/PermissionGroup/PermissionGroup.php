<?php

namespace App\Modules\PermissionGroup;

use App\Traits\Scopes;
use App\Traits\Mutators;
use App\Modules\Permission\Permission;
use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model 
{
    use Scopes, Mutators;

    protected $table = 'permission_groups';

    protected $fillable = [
        'group_permission_id',
        'permission_id',
    ];

    public function scopeWhereGroupPermissionId($query, $groupPermissionId)
    {
        return $query->where('group_permission_id', $groupPermissionId);
    }
}