<?php 

declare (strict_types = 1);

namespace App\Core;

class BaseValidation
{
    public static function getRules(?int $id = null, ?array $fields = []): array
    {
        $rules = static::rules($id);

        if ($fields && is_array($fields))
        {
            $extractRules = [];

            foreach ($fields as $field) 
            {
                if (isset($rules[$field])) 
                {
                    $extractRules[$field] = $rules[$field];
                }
            }

            return $extractRules;
        }

        return $rules;
    }

    public function getIDRule($table)
    {
        return [
            'bail',
            'required',
            'integer',
            "exists:{$table},id"
        ];
    }

    public function regexPassword()
    {
        return 'regex:/^.*(?=.{3,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\d\X])(?=.*[@!$#%]).*$/';
    }
}