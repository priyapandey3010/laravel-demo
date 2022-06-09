<?php 

declare (strict_types = 1);

namespace App\Modules\Designation;

use App\Core\BaseValidation;
use Illuminate\Validation\Rule;
use App\Rules\AlphaSpace;

class DesignationValidation extends BaseValidation 
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
                'unique:designations,name,'.$id,
                new AlphaSpace
            ],
            'description' => [
                'nullable',
                'string',
                'max:400'
            ],
            'is_active' => [
                'boolean'
            ]
        ];
    }
}