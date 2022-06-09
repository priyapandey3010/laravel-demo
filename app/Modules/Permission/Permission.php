<?php

namespace App\Modules\Permission;

use App\Traits\Scopes;
use App\Traits\Mutators;
use App\Modules\PermissionGroup\PermissionGroup;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model 
{
    use Scopes, Mutators;

    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}