<?php

namespace Mediamouse\Users\Filament\Pages;

use Carbon\Carbon;
use Closure;
use Faker\Provider\Text;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Contracts\HasFormActions;
use Filament\Pages\Page;
use Filament\Forms;
use Illuminate\Support\Facades\Auth;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Users\Models\Language;
use Mediamouse\Users\Models\User;
use Mediamouse\Users\Validate\Password;
use Mediamouse\Users\Validate\ShouldEqual;

class ChangePassword extends Page implements HasFormActions
{
    use \Filament\Pages\Concerns\HasFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $slug = 'change-password';

    protected static string $view = 'filament-spatie-laravel-settings-plugin::pages.settings-page';

    public $data;

    public string $password;
    public string $new_password;
    public string $repeat_password;

    protected static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? static::$title ?? __('mediamouse-users::pages/change-password.title');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function getTitle(): string
    {
        return static::$title ?? __('mediamouse-users::pages/change-password.title');
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Fieldset::make(__('mediamouse-users::pages/change-password.password-settings'))
                    ->columnSpan(1)
                    ->columns(1)
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->required()
                            ->rules([
                                function () {
                                    return function (string $attribute, $value, Closure $fail) {
                                        /** @var User $current_user */
                                        $current_user = Filament::auth()->user();
                                        if (!$current_user->validatePassword($value)) {
                                            $fail("The {$attribute} is invalid.");
                                        }
                                    };
                                },
                            ])
                            ->inlineLabel()
                            ->label(__('mediamouse-users::pages/change-password.current-password')),
                        TextInput::make('new_password')
                            ->password()
                            ->rules([
                                new Password(),
                            ])
                            ->required()
                            ->inlineLabel()
                            ->label(__('mediamouse-users::pages/change-password.new-password')),
                        TextInput::make('repeat_password')
                            ->password()
                            ->required()
                            ->rules([
                                new ShouldEqual('new_password', __('mediamouse-users::pages/change-password.new-password'), $this),
                            ])
                            ->inlineLabel()
                            ->label(__('mediamouse-users::pages/change-password.repeat-password')),
                    ]),
            ])
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        /** @var User $current_user */
        $current_user = Filament::auth()->user();

        if($current_user->validatePassword($data['password'])) {
            if($current_user->updatePassword($data['new_password'])) {
                request()->session()->put('password_hash_web', $current_user->password);

                Notification::make()
                    ->success()
                    ->title(__('mediamouse-users::pages/change-password.password-updated'))
                    ->send();

                response()->redirectTo(route('filament.pages.change-password'));
            }
        }
    }


    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('mediamouse-users::pages/change-password.button'))
            ->submit('submit')
            ->keyBindings(['mod+s']);
    }

}

