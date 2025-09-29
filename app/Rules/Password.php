<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class Password implements Rule
{
    protected $minLength = 12;
    protected $requireUppercase = true;
    protected $requireNumeric = true;
    protected $requireSpecialChar = true;

    public function passes($attribute, $value)
    {
        $value = (string) $value;

        if (strlen($value) < $this->minLength) {
            return false;
        }

        if ($this->requireUppercase && !preg_match('/(\p{Lu})/u', $value)) {
            return false;
        }

        if ($this->requireNumeric && !preg_match('/\d/', $value)) {
            return false;
        }

        if ($this->requireSpecialChar && !preg_match('/[^\p{L}\p{N}\s]/u', $value)) {
            return false;
        }

        return true;
    }

    public function message()
    {
        $message = 'The :attribute must be at least ' . $this->minLength . ' characters long';
        
        if ($this->requireUppercase) {
            $message .= ', contain at least one uppercase letter';
        }
        
        if ($this->requireNumeric) {
            $message .= ', contain at least one number';
        }
        
        if ($this->requireSpecialChar) {
            $message .= ', and contain at least one special character';
        }
        
        return $message . '.';
    }
}
