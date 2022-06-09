<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ValidateRecentPassword implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->user = User::findOrFail($id);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $passwordHistories = $this->user->passwordHistories()->take(env('PASSWORD_HISTORY_TAKE'))->get();
        
        foreach ($passwordHistories as $passwordHistory) 
        {
            if (Hash::check($value, $passwordHistory->password)) 
                return false;
        }
        
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute should not match with previous last '. env('PASSWORD_HISTORY_TAKE') .' entered passwords';
    }
}
