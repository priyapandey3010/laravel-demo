<?php 

declare (strict_types = 1);

namespace App\Modules\Court;

use App\Core\BaseValidation;
use Illuminate\Validation\Rule;
use App\Rules\AlphaSpace;

class CourtValidation extends BaseValidation 
{
    protected static function rules(?int $id = null): array
    {
        return [
            'bench_id' => [
                'bail',
                'required', 
                'int'
            ],
            'court_number' => [
                'bail',
                'required', 
                'string', 
                'alpha_num'
            ],
            'court_name' => [
                'bail',
                'required',
                'string',
                'min:1',
                'max:50',
                new AlphaSpace()
            ],
            'is_active' => [
                'boolean'
            ]
        ];
    }
}