<?php 

declare (strict_types = 1);

namespace App\Modules\Role;

use App\Core\BaseValidation;
use Illuminate\Validation\Rule;
use App\Rules\AlphaSpace;

class RoleValidation extends BaseValidation 
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
                new AlphaSpace
            ],
            'is_active' => [
                'boolean'
            ]
        ];
    }
}