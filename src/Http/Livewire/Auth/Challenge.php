<?php

namespace Mediamouse\Users\Http\Livewire\Auth;

use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Facades\Filament;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\SimplePage;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Mediamouse\Users\Enums\LoginAttemptStatus;
use Mediamouse\Users\Http\Responses\Auth\Login\EnterNewPasswordResponse;
use Mediamouse\Users\Http\Responses\Auth\Login\LoginResponse;
use Mediamouse\Users\Http\Responses\Auth\Login\TimeOutResponse;
use Mediamouse\Users\Models\LoginAttempt;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @property ComponentContainer $form
 */
class Challenge extends SimplePage implements HasForms
{
    use InteractsWithForms;
    use WithRateLimiting;

    public ?string $challenge = '';

    public ?string $message = '';

    protected static string $view = 'mediamouse-users::challenge';

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function mount(): void
    {
        if (Filament::auth()->check() || !$this->getUser()) {
            redirect()->intended(Filament::getUrl());
            return;
        }

        $this->form->fill();
    }

    /**
     * @return Responsable
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ValidationException
     */
    public function submit()
    {
        try {
            $this->rateLimit(522);
        } catch (TooManyRequestsException $exception) {
            throw ValidationException::withMessages([
                'challenge' => __('mediamouse-users::login.messages.throttled', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]),
            ]);
        }

        $data = $this->form->getState();
        $user = $this->getUser();

        if(!$user) {
            return app(TimeOutResponse::class);
        }

        if(session()->get('login.challenge') !== $data['challenge']) {
            throw ValidationException::withMessages([
                'challenge' => 'Challenge code is not correct',
            ]);
        }

        \Mediamouse\Users\Helper\Login::login($this->getUser());

        $attempt = $this->getLoginAttempt();
        $attempt->status = LoginAttemptStatus::SUCCESSFUL;
        $attempt->save();

        $token = $user->passwordNeedsReset();
        if($token !== null) {
            session()->put('login.reset-token', $token->token);
            return app(EnterNewPasswordResponse::class);
        }
        return app(LoginResponse::class);
    }

    public function returnToLogin() {
        session()->put([
            'login.email' => null,
            'login.challenge' => null,
            'login.error' => null,
        ]);

        return app(LoginResponse::class);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function requestNewCode() {
        $attempt = $this->getLoginAttempt();
        $attempt->token = $this->getUser()->createChallengeCode();
        $attempt->save();

        $this->getUser()->sendLoginChallenge($this->getLoginAttempt());

        request()->session()->put([
            'login.challenge' => $attempt->token,
        ]);

        $this->message = 'A new challenge code has been sent to your email address';

        $this->form->fill([
            'challenge' => env('APP_ENV') === 'local' ? session()->get('login.challenge') : ''
        ]);

    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getUser(): ?User {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return User::find(session()->get('login.id'));
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getLoginAttempt(): ?LoginAttempt {
        return LoginAttempt::find(session()->get('login.attempt'));
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('challenge')
                ->label('Challenge Code')
                ->default(env('APP_ENV') === 'local' ? session()->get('login.challenge') : '')
                ->required()
                ->autocomplete(false),
        ];
    }

}
