<?php 

declare (strict_types = 1);

namespace App\Modules\AuditTrail;

use App\Core\BaseValidation;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class AuditTrailValidation extends BaseValidation 
{
    protected static function rules(?int $id = null): array
    {
        return [
            'first_name' => [
                'bail',
                'required',
                'string',
                'min:3',
                'max:50',
                new AlphaSpace
            ]           
        ];
    }

    public static function messages()
    {
        return [
            'password.regex' => 'The :attribute should be of 8 characters: contain, One Uppercase, One Number and one special character '
        ];
    }
}