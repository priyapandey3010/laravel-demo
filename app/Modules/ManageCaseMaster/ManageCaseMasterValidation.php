<?php 

declare (strict_types = 1);

namespace App\Modules\ManageCaseMaster;

use App\Core\BaseValidation;
use Illuminate\Validation\Rule;

class ManageCaseMasterValidation extends BaseValidation 
{
    protected static function rules(?int $id = null): array
    {
        return [
            'case_type_id' => [
                'bail',
                'required',
                'integer',
                'exists:case_types,id'
            ],
            'case_number' => [
                'bail',
                'required', 
                'string', 
                'min:1',
                'max:250', 
            ],
            'case_title' => [
                'bail',
                'required',
                'string',
                'max:100'
            ],
            'is_active' => [
                'boolean'
            ]
        ];
    }
}