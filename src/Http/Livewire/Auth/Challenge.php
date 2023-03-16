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
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Mediamouse\Users\Http\Responses\Auth\Login\LoginResponse;
use Mediamouse\Users\Http\Responses\Auth\Login\TimeOutResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @property ComponentContainer $form
 */
class Challenge extends Component implements HasForms
{
    use InteractsWithForms;
    use WithRateLimiting;

    public ?string $challenge = '';

    public ?string $message = '';

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $this->form->fill();
    }

    /**
     * @return Responsable
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ValidationException
     */
    public function challenge(): Responsable
    {
        try {
            $this->rateLimit(522);
        } catch (TooManyRequestsException $exception) {
            throw ValidationException::withMessages([
                'challenge' => __('filament::login.messages.throttled', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]),
            ]);
        }

        $data = $this->form->getState();

        if(!$this->getUser()) {
            return app(TimeOutResponse::class);
        }

        if(session()->get('login.challenge') !== $data['challenge']) {
            throw ValidationException::withMessages([
                'challenge' => 'Challenge code is not correct',
            ]);
        }

        Filament::auth()->login($this->getUser());

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
        request()->session()->put([
            'login.challenge' => $this->getUser()->sendLoginChallenge(),
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
        return User::query()->where('email', session()->get('login.email'))->first();
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
                ->autocomplete(),
        ];
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function render(): View
    {
        return view('mediamouse-users::challenge')
            ->layout('filament::components.layouts.card', [
                'title' => __('filament::login.title'),
            ]);
    }

}
