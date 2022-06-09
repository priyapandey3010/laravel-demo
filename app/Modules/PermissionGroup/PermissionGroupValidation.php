<?php 

declare (strict_types = 1);

namespace App\Modules\PermissionGroup;

use App\Core\BaseValidation;

class PermissionGroupValidation extends BaseValidation 
{
    protected static function rules(?int $id = null): array
    {
        return [
            'permission_id' => [
                'bail',
                'required',
                'integer',
                'exists:permissions,id'
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