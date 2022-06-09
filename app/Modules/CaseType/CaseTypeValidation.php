<?php 

declare (strict_types = 1);

namespace App\Modules\CaseType;

use App\Core\BaseValidation;
use Illuminate\Validation\Rule;

class CaseTypeValidation extends BaseValidation 
{
    protected static function rules(?int $id = null): array
    {
        return [
            'case_type' => [
                'bail',
                'required',
                'string',
                'min:1',
                'max:50',
                'alpha_dash',
                'unique:case_types,case_type,'. $id
            ],
            'is_active' => [
                'boolean'
            ]
        ];
    }
}