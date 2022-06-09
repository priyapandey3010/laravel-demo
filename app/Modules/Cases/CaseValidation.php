<?php 

declare (strict_types = 1);

namespace App\Modules\Cases;

use App\Core\BaseValidation;
use Illuminate\Validation\Rule;

class CaseValidation extends BaseValidation 
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
            // 'category_id' => [
            //     'bail',
            //     'required',
            //     'integer',
            //     'exists:categories,id'
            // ],
            // 'item_number' => [
            //     'bail',
            //     'required',
            //     'string',
            //     'max:100',
            //     'alpha_dash',
            //     'unique:cases,item_number,'.$id
            // ],
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
            // 'status_id' => [
            //     'bail',
            //     'nullable',
            //     'integer',
            //     'exists:status,id'
            // ],
            // 'bench_id' => [
            //     'bail',
            //     'integer',
            //     'exists:bench,id'
            // ],
            // 'court_id' => [
            //     'bail',
            //     'integer',
            //     'exists:court,id'
            // ],
            'is_active' => [
                'boolean'
            ]
        ];
    }
}