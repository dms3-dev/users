<?php

namespace Mediamouse\Users\Validate;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Mediamouse\Users\Settings\UserManagementSettings;

class Password implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail) : void {
        $settings = new UserManagementSettings();
        if(
            ($settings->non_capital_letters && !preg_match('/[a-z]/', $value)) ||
            ($settings->capital_letters && !preg_match('/[A-Z]/', $value)) ||
            ($settings->numbers && !preg_match('/[0-9]/', $value)) ||
            ($settings->special_characters && !preg_match('/[^a-zA-Z0-9]/', $value))
        ) {
            $fail("Your password should contain at least 1 letter, 1 capital, 1 number and 1 special character!");
        }
    }
}
