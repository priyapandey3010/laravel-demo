<?php 

declare (strict_types = 1);

namespace App\Modules\Status;

use App\Core\BaseValidation;
use Illuminate\Validation\Rule;

class StatusValidation extends BaseValidation 
{
    protected static function rules(?int $id = null): array
    {
        return [
            'status' => [
                'bail',
                'required',
                'string',
                'min:1',
                'max:100',
            ],
            'colour_code' => [
                'bail',
                'required',
            ],
            'is_default' => [
                'nullable',
                'boolean'
            ],
            'is_active' => [
                'boolean'
            ]
        ];
    }
}