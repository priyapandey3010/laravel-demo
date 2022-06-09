<?php

namespace App\Rules;

use App\Modules\Cases\Cases;
use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class ValidateUniqueCaseNumber implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        
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
        $calendar = request()->calendar;
        $caseNumber = request()->case_number;
        $model = Cases::where('case_number', $caseNumber)
        ->whereDate('created_date', Carbon::createFromFormat('d/m/Y', trim($calendar)))
        ->first();
        return $model ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute already created for this date';
    }
}
