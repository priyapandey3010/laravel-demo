<?php 

declare (strict_types = 1);

namespace App\Modules\Category;

use App\Core\BaseValidation;
use Illuminate\Validation\Rule;
use App\Rules\AlphaSpaceComma;

class CategoryValidation extends BaseValidation 
{
    protected static function rules(?int $id = null): array
    {
        return [
            'name' => [
                'bail',
                'required',
                'string',
                'min:1',
                'max:50',
                new AlphaSpaceComma()
            ],
            'is_active' => [
                'boolean'
            ]
        ];
    }
}