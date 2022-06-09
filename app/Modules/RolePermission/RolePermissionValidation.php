<?php 

declare (strict_types = 1);

namespace App\Modules\RolePermission;

use App\Core\BaseValidation;

class RolePermissionValidation extends BaseValidation 
{
    protected static function rules(?int $id = null): array
    {
        return [
            'role_id' => [
                'bail',
                'required',
                'integer',
                'exists:roles,id'
            ],
            'permissions' => [
                'bail',
                'required',
                'array',
                'min:1'
            ],
            'permissions.*' => [
                'bail',
                'required',
                'integer',
                'exists:permissions,id'
            ]
        ];
    }
}