<?php 

declare (strict_types = 1);

namespace App\Modules\DisplayBoard;

use App\Core\BaseValidation;
use Illuminate\Validation\Rule;
use App\Rules\AlphaSpaceComma;
use App\Rules\ValidateUniqueCaseNumber;

class DisplayBoardValidation extends BaseValidation 
{
    protected static function rules(?int $id = null): array
    {
        return [
            'calendar' => [
                'bail',
                'required',
                //'date',
            ],
            'case_type_id' => [
                'bail',
                'required',
                'integer',
                'exists:case_types,id'
            ],
            'case_number' => [
                'bail',
                'required',
                'exists:manage_case_master,case_number',
                new ValidateUniqueCaseNumber()
            ],
            'case_title' => [
                'bail',
                'required',
                'exists:manage_case_master,case_title'
            ],
            'court_id' => [
                'bail',
                Rule::requiredIf(auth()->user()->role_id == config('role.bench-admin')),
                'integer',
                'exists:court,id'
            ],
            'category_id' => [
                'bail',
                'required',
                'integer',
                'exists:categories,id'
            ],
            'item_number' => [
                'bail',
                'required',
                'unique:display_board,item_number'
            ]
        ];
    }
}