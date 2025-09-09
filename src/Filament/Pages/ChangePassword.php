<?php

namespace Mediamouse\Users\Filament\Pages;

use Carbon\Carbon;
use Closure;
use Faker\Provider\Text;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Forms;
use Illuminate\Support\Facades\Auth;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Users\Models\Language;
use Mediamouse\Users\Models\User;
use Mediamouse\Users\Validate\Password;
use Mediamouse\Users\Validate\ShouldEqual;
use Filament\Resources\Pages\Concerns;

class ChangePassword extends Page implements HasForms
{
    use InteractsWithForms;
    use InteractsWithFormActions;
    use HasUnsavedDataChangesAlert;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $slug = 'change-password';

    protected static string $view = 'filament-spatie-laravel-settings-plugin::pages.settings-page';

    public $data;

    public string $password;
    public string $new_password;
    public string $repeat_password;

    public static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? static::$title ?? __('mediamouse-users::pages/password.title');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function getTitle(): string
    {
        return static::$title ?? __('mediamouse-users::pages/password.title');
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Fieldset::make(__('mediamouse-users::pages/password.password-settings'))
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
                            ->label(__('mediamouse-users::pages/password.current-password')),
                        TextInput::make('new_password')
                            ->password()
                            ->rules([
                                new Password(Filament::auth()->user()),
                            ])
                            ->required()
                            ->inlineLabel()
                            ->label(__('mediamouse-users::pages/password.new-password')),
                        TextInput::make('repeat_password')
                            ->password()
                            ->required()
                            ->rules([
                                new ShouldEqual('new_password', __('mediamouse-users::pages/password.new-password'), $this),
                            ])
                            ->inlineLabel()
                            ->label(__('mediamouse-users::pages/password.repeat-password')),
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
                    ->title(__('mediamouse-users::pages/password.password-updated'))
                    ->send();

                response()->redirectTo(static::getUrl());
            }
        }
    }


    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('mediamouse-users::pages/password.button'))
            ->submit('submit')
            ->keyBindings(['mod+s']);
    }

}

