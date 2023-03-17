<?php

namespace Mediamouse\Users\Http\Livewire\Auth;

use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Facades\Filament;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Filament\Http\Livewire\Auth\Login as BaseLogin;
use Mediamouse\Users\Http\Responses\Auth\Login\ResetPasswordLinkSendResponse;
use Mediamouse\Users\Http\Responses\Auth\TwoFactorLoginResponse;

/**
 * @property ComponentContainer $form
 */
class EnterNewPassword extends Component implements HasForms
{
    use InteractsWithForms;
    use WithRateLimiting;

    public ?string $email = '';
    public ?string $password = '';

    private ?User $user = null;

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
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
        $user = User::query()->where('email', $data['email']);
        $user?->sendForgotPasswordLink();

        return app(ResetPasswordLinkSendResponse::class);

    }

    private function getUser(): ?User {
        return $this->user;
    }

    private function loginRateLimit() {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            throw ValidationException::withMessages([
                'email' => __('filament::login.messages.throttled', [
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
                ->label(__('filament::login.fields.email.label'))
                ->email()
                ->required(),
        ];
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function render(): View
    {
        return view('mediamouse-users::forgot-password')
            ->layout('filament::components.layouts.card', [
                'title' => __('filament::login.title'),
            ]);
    }
}
