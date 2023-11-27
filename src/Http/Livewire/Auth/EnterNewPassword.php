<?php

namespace Mediamouse\Users\Http\Livewire\Auth;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Facades\Filament;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Mediamouse\Users\Enums\PasswordResetStatus;
use Mediamouse\Users\Http\Responses\Auth\Login\LoginResponse;
use Mediamouse\Users\Http\Responses\Auth\Login\ResetPasswordLinkSendResponse;
use Mediamouse\Users\Http\Responses\Auth\Login\TimeOutResponse;
use Mediamouse\Users\Models\Password;
use Mediamouse\Users\Models\PasswordReset;
use Mediamouse\Users\Settings\UserManagementSettings;

/**
 * @property ComponentContainer $form
 */
class EnterNewPassword extends Component implements HasForms
{
    use InteractsWithForms;
    use WithRateLimiting;

    public ?string $email = '';
    public ?string $password = '';

    public ?string $token = '';
    public bool $isLoggedIn = false;

    private ?User $user = null;

    public function mount(): void
    {
        $this->token = request()->token;
        if(!$this->token) {
            $this->token = session()->get('login.reset-token');
            $this->isLoggedIn = true;
            session()->put('login.reset-token', '');
        }
        if($this->token === null || $this->token == '') {
            redirect()
                ->intended(Filament::getUrl())
            ;
            return;
        }
        $passReset = $this->getPasswordReset();

        if(
            (!$this->isLoggedIn && Filament::auth()->check()) ||
            $passReset === null ||
            $passReset->status !== PasswordResetStatus::CREATED ||
            $passReset->created_at < Carbon::now()->addMinutes(-30)
            ) {
            session()->put('login.error', 'invalid-link');
            redirect()
                ->intended(Filament::getUrl())
                        ;
        }

        $passReset->status = PasswordResetStatus::ACTIVE;
        $passReset->save();

        $this->form->fill();

    }

    /**
     * @throws ValidationException
     */
    public function resetPassword()
    {
        $this->loginRateLimit();

        $data = $this->form->getState();

        if($data['new-password'] !== $data['repeat-password']) {
            throw ValidationException::withMessages([
                'new-password' => __('mediamouse-users::pages/login.error-password-not-equal'),
            ]);
        }

        $passReset = $this->getPasswordReset();

        if(!$passReset || $passReset->status !== PasswordResetStatus::ACTIVE) {
            return app(TimeOutResponse::class);
        }

        $user = $passReset->user;

        $user->updatePassword($data['new-password']);

        $passReset->status = PasswordResetStatus::USED;
        $passReset->save();

        if($this->isLoggedIn) {
            Filament::auth()->login($user);
        }

        return app(LoginResponse::class);

    }

    private function getPasswordReset(): ?PasswordReset {
        return PasswordReset::query()->where('token', $this->token)->first();

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
            TextInput::make('new-password')
                ->label(__('filament::login.fields.password.label'))
                ->password()
                ->minLength(8)
                ->maxLength(20)
                ->rules([
                    new \Mediamouse\Users\Validate\Password()
                ])
                ->required(),
            TextInput::make('repeat-password')
                ->label(__('mediamouse-users::pages/login.repeat-password'))
                ->password()
                ->minLength(app(UserManagementSettings::class)->password_min_length)
                ->maxLength(app(UserManagementSettings::class)->password_max_length)
                ->required()
                ,
        ];
    }

    public function returnToLogin() {
        return app(LoginResponse::class);
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function render(): View
    {
        return view('mediamouse-users::enter-new-password')
            ->layout('filament::components.layouts.card', [
                'title' => __('mediamouse-users::pages/login.title-reset-password'),
            ]);
    }
}
