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
use Mediamouse\Users\Enums\LoginAttemptStatus;
use Mediamouse\Users\Enums\UserTwoFactor;
use Mediamouse\Users\Filament\Pages\ManageUserManagementSettings;
use Mediamouse\Users\Http\Responses\Auth\Login\EnterNewPasswordResponse;
use Mediamouse\Users\Http\Responses\Auth\Login\ForgotPasswordResponse;
use Mediamouse\Users\Http\Responses\Auth\TwoFactorLoginResponse;
use Mediamouse\Users\Models\LoginAttempt;
use Mediamouse\Users\Settings\UserManagementSettings;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Illuminate\Support\Facades\Hash;

/**
 * @property ComponentContainer $form
 */
class Login extends Component implements HasForms
{
    use InteractsWithForms;
    use WithRateLimiting;

    public ?string $email = '';
    public ?string $password = '';

    public string $message_text = '';
    public string $message_class = '';

    private ?User $user = null;

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
            return;
        }

        $this->form->fill();

        switch(session()->get('login.error')) {
            case 'timeout' :
                $this->message_class = 'text-danger-500';
                $this->message_text = 'Your session is expired please try again';
                break;
            case 'invalid-link' :
                $this->message_class = 'text-danger-500';
                $this->message_text = 'The link used to reset your password is not valid';
                break;
            case 'forgot-password' :
                $this->message_text = 'If your email address is known in our system a link to reset your password has been sent';
                break;
        }

        session()->put('login.error', null);
    }

    /**
     * @throws ValidationException
     */
    public function authenticate()
    {
        $data = $this->form->getState();

        /** @var User $user */
        $user = User::query()->where('email', $data['email'])->first();
        $password = $data['password'];

        $attempt = $user->createLoginAttempt();

        $this->loginRateLimit();
        $this->validateUserLoginOrFail($user, $password, $attempt);

        if($user->two_factor == UserTwoFactor::NONE) {
            $this->loginUser($user, $attempt);

            $token = $user->passwordNeedsReset();
            if($token !== null) {
                session()->put('login.reset-token', $token->token);
                return app(EnterNewPasswordResponse::class);
            }
            return app(LoginResponse::class);
        }

        $user->sendLoginChallenge($attempt);
        $this->writeToSession($user, $attempt);

        return app(TwoFactorLoginResponse::class);
    }

    public function forgotPassword() {
        return app(ForgotPasswordResponse::class);
    }

    /**
     * @throws ValidationException
     */
    private function validateUserLoginOrFail(User $user, string $password, LoginAttempt $attempt): true {

        if(strlen($user->password ) == 40 && $user->password == sha1($password . env('LOGIN_PASSKEY'))) {
            $user->password = Hash::make($password);
            $user->save();
        }

        if($user->isBlocked()) {
            $attempt->status = LoginAttemptStatus::FAILED;
            $attempt->save();

            throw ValidationException::withMessages([
                'email' => __('filament::login.messages.failed'),
            ]);

        }
        if(!Filament::auth()->validate([
            'email' => $user->email,
            'password' => $password,
        ])) {
            $attempt->status = LoginAttemptStatus::FAILED;
            $attempt->save();

            $user->sendFailedLoginAttempt();

            throw ValidationException::withMessages([
                'email' => __('filament::login.messages.failed'),
            ]);
        }

        return true;
    }

    private function getUser(): ?User {
        return $this->user;
    }

    /**
     * @throws ValidationException
     */
    private function loginRateLimit() {
        try {
            $this->rateLimit(app(UserManagementSettings::class)->max_failed_login_attempts_per_ip, env('APP_ENV') == 'local' ? 10 : 1800);
        } catch (TooManyRequestsException $exception) {
            throw ValidationException::withMessages([
                'email' => 'Too many login attempts. Please try again later.',
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

    private function writeToSession(User $user, LoginAttempt $attempt)
    {
        request()->session()->put([
            'login.id' => $user->id,
            'login.email' => $user->email,
            'login.challenge' => $attempt->token,
            'login.attempt' => $attempt->id,
        ]);
    }

    private function loginUser(User $user, LoginAttempt $attempt)
    {
        Filament::auth()->login($user);

        $attempt->status = LoginAttemptStatus::SUCCESSFUL;
        $attempt->save();
    }
}
