<?php

namespace Mediamouse\Users\Validate;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Mediamouse\Users\Models\User;
use Mediamouse\Users\Settings\UserManagementSettings;
use Mediamouse\Laravel\Support\Arr;

class Password implements ValidationRule
{
    private ?User $user;

    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    public function validate(string $attribute, mixed $value, Closure $fail) : void {
        $settings = new UserManagementSettings();
        if(
            ($settings->non_capital_letters && !preg_match('/[a-z]/', $value)) ||
            ($settings->capital_letters && !preg_match('/[A-Z]/', $value)) ||
            ($settings->numbers && !preg_match('/[0-9]/', $value)) ||
            ($settings->special_characters && !preg_match('/[^a-zA-Z0-9]/', $value))
        ) {
            $errors = [];
            if($settings->non_capital_letters) $errors[] = __('mediamouse-users::pages/login.error-password-invalid-letter');
            if($settings->capital_letters) $errors[] = __('mediamouse-users::pages/login.error-password-invalid-capital');
            if($settings->numbers) $errors[] = __('mediamouse-users::pages/login.error-password-invalid-number');
            if($settings->special_characters) $errors[] = __('mediamouse-users::pages/login.error-password-invalid-special');

            $fail(__('mediamouse-users::pages/login.error-password-invalid',
                    [
                        'conditions' => Arr::join(
                                                $errors,
                                                __('mediamouse-users::pages/login.error-password-invalid-glue'),
                                                __('mediamouse-users::pages/login.error-password-invalid-final-glue')
                                            )
                    ]));
        }

        if($this->user && $this->user->passwordAlreadyUsed($value)) {
            $fail(__('mediamouse-users::pages/password.password-used'));
        }
    }
}
