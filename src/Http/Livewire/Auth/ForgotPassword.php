<?php

namespace Mediamouse\Users\Http\Livewire\Auth;

use App\Forms\Components\TextDisplay;
use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Facades\Filament;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\SimplePage;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Filament\Http\Livewire\Auth\Login as BaseLogin;
use Mediamouse\Users\Http\Responses\Auth\Login\LoginResponse;
use Mediamouse\Users\Http\Responses\Auth\Login\ResetPasswordLinkSendResponse;
use Mediamouse\Users\Http\Responses\Auth\TwoFactorLoginResponse;

/**
 * @property ComponentContainer $form
 */
class ForgotPassword extends SimplePage implements HasForms
{
    use InteractsWithForms;
    use WithRateLimiting;

    public ?string $email = '';
    public ?string $password = '';
    public ?string $pageTitle = '';

    private ?User $user = null;

    protected static string $view = 'mediamouse-users::forgot-password';

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
            return;
        }

        $this->form->fill();

    }

    /**
     * @throws ValidationException
     */
    public function forgotPassword()
    {
        $this->loginRateLimit();

        $data = $this->form->getState();

        /** @var User $user */
        $user = User::query()->where('email', $data['email'])->first();
        $user?->sendForgotPasswordLink();



        return app(ResetPasswordLinkSendResponse::class);

    }

    public function returnToLogin() {
        return app(LoginResponse::class);
    }

    private function getUser(): ?User {
        return $this->user;
    }

    private function loginRateLimit() {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            throw ValidationException::withMessages([
                'email' => __('mediamouse-users::login.messages.throttled', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]),
            ]);
        }

    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->label(__('mediamouse-users::login.fields.email.label'))
                ->default(config('app.env') === 'local' ? 'support@mediamouse.nl' : '')
                ->email()
                ->required(),
        ];
    }
}
