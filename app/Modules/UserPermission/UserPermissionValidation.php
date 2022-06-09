<?php 

declare (strict_types = 1);

namespace App\Modules\UserPermission;

use App\Core\BaseValidation;

class UserPermissionValidation extends BaseValidation 
{
    protected static function rules(?int $id = null): array
    {
        return [
            'user_id' => [
                'bail',
                'required',
                'integer',
                'exists:users,id'
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