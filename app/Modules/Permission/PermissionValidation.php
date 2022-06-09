<?php 

declare (strict_types = 1);

namespace App\Modules\Permission;

use App\Core\BaseValidation;

class PermissionValidation extends BaseValidation 
{
    protected static function rules(?int $id = null): array
    {
        return [
            'name' => [
                'bail',
                'required',
                'string',
                'min:1',
                'max:100',
                'alpha_dash'
            ],
            'description' => [
                'bail',
                'nullable',
                'max:400'
            ],
            'is_active' => [
                'boolean'
            ]
        ];
    }
}