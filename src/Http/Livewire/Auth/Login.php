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
use Mediamouse\Users\Http\Responses\Auth\TwoFactorLoginResponse;

/**
 * @property ComponentContainer $form
 */
class Login extends Component implements HasForms
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

        if(session()->get('login.error') === 'timeout') {
            session()->put([
                'login.error' => null
            ]);

            throw ValidationException::withMessages([
                'email' => 'Timeout',
            ]);
        }
    }

    /**
     * @throws ValidationException
     */
    public function authenticate()
    {
        $this->loginRateLimit();

        $data = $this->form->getState();

        if(!$this->validateUserLogin($data['email'], $data['password'])) {

            throw ValidationException::withMessages([
                'email' => __('filament::login.messages.failed'),
            ]);
        }

        request()->session()->put([
            'login.email' => $data['email'],
            'login.challenge' => $this->getUser()->sendLoginChallenge(),
        ]);

        return app(TwoFactorLoginResponse::class);

    }

    private function validateUserLogin(string $email, string $password) {
        if(Filament::auth()->validate([
            'email' => $email,
            'password' => $password,
        ])) {
            $this->user = User::query()->where('email', $email)->first();
            return true;
        }
        return false;
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
                ->required()
                ->autocomplete(),
            TextInput::make('password')
                ->label(__('filament::login.fields.password.label'))
                ->password()
                ->required(),
        ];
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function render(): View
    {
        return view('mediamouse-users::login')
            ->layout('filament::components.layouts.card', [
                'title' => __('filament::login.title'),
            ]);
    }
}
