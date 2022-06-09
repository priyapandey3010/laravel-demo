<?php 

declare (strict_types = 1);

namespace App\Modules\User;

use App\Core\BaseValidation;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use App\Rules\AlphaSpace;
use App\Rules\FileName;
use App\Rules\ValidateRecentPassword;

class UserValidation extends BaseValidation 
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
            ],
            'last_name' => [
                'bail',
                'nullable',
                'string',
                'min:3',
                'max:50',
                new AlphaSpace
            ],
            'username' => [
                'bail',
                'required',
                'string',
                'min:5',
                'max:50',
                'alpha_dash',
                'unique:users,username,'.$id
            ],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                'unique:users,email,'.$id
            ],
            'password' => [
                'required', 
                'confirmed', 
                'min:8',
                parent::regexPassword(),
                isset($id) ? new ValidateRecentPassword($id) : null
            ],
            'password_confirmation' => [
                'required', 
            ],
            'designation_id' => [
                'bail',
                'required',
                'integer',
                'exists:designations,id'
            ],
            'department_id' => [
                'bail',
                'required',
                'integer',
                'exists:departments,id'
            ],
            'role_id' => [
                'bail',
                'required',
                'integer',
                'exists:roles,id'
            ],
            'category_type' => [
                'bail',
                'required',
                Rule::in([
                    config('category.website'),
                    config('category.display_board')
                ])
            ],
            'contact_number' => [
                'bail',
                'required',
                'string',
                'min:10',
                'max:10'
            ],
            'image' => [
                'bail',
                'required',
                'mimes:jpg,gif,png',
                'mimetypes:image/jpeg,image/gif,image/png',
                'max:50',
                new FileName(),
            ],
            'bench_id' => [
                'bail',
                'nullable',
                'exists:bench,id',
            ],
            'court_id' => [
                'bail',
                'nullable',
                'exists:court,id',
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