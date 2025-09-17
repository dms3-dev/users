<?php

namespace Mediamouse\Users\Filament\Pages;

use Carbon\Carbon;
use Filament\Actions\ActionGroup;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Users\Models\Language;
use Mediamouse\Users\Models\User;

/**
 * @property Form form
 */
class UserSettings extends Page implements Forms\Contracts\HasForms
{
//    use InteractsWithForms;
//    use InteractsWithFormActions;
    use HasUnsavedDataChangesAlert;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cog';
    protected static ?string $slug = 'user-settings';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->disabled(! $this->canEdit())
            ->inlineLabel($this->hasInlineLabels())
            ->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->schema($this->getFormSchema());
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
            ]);
    }



    public static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? static::$title ?? __('mediamouse-users::pages/user-settings.title');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function getTitle(): string
    {
        return static::$title ?? __('mediamouse-users::pages/user-settings.title');
    }

    public function fillForm(): void
    {
        $this->callHook('beforeFill');

        /** @var User $current_user */
        $current_user = auth()->user();

        $data = [
            'name' => $current_user->name,
            'email' => $current_user->email,
            'language_iso' => $current_user->language_iso,

            'date_format' => $current_user->getUserSetting('mediamouse-users.date_format'),
            'time_format' => $current_user->getUserSetting('mediamouse-users.time_format'),
            'dateTime_format' => $current_user->getUserSetting('mediamouse-users.dateTime_format'),
            'number_format' => $current_user->getUserSetting('mediamouse-users.number_format'),
            'csv_delimiter' => $current_user->getUserSetting('mediamouse-users.csv_delimiter'),
            'csv_enclosure' => $current_user->getUserSetting('mediamouse-users.csv_enclosure'),
            'csv_new_line' => $current_user->getUserSetting('mediamouse-users.csv_new_line'),
        ];


        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    private function fieldsetLanguageSettings(): Schemas\Components\Fieldset
    {
        return
            Schemas\Components\Fieldset::make(__('mediamouse-users::pages/user-settings.language-settings'))
                ->columnSpan(1)
                ->columns(1)
                ->schema([
                    Select::make('language_iso')
                        ->inlineLabel()
                        ->options(Language::query()->pluck('name', 'iso'))
                        ->label(__('mediamouse-users::pages/user-settings.language')),
                ]);
    }

    private function fieldsetDetailsSettings(): Schemas\Components\Fieldset
    {
        return
            Schemas\Components\Fieldset::make(__('mediamouse-users::pages/user-settings.user-details'))
                ->columnSpan(1)
                ->columns(1)
                ->schema([
                    TextInput::make('name')
                        ->inlineLabel()
                        ->label(__('mediamouse-users::pages/user-settings.full-name')),
                    TextInput::make('email')
                        ->inlineLabel()
                        ->label(__('mediamouse-users::pages/user-settings.email')),
                ]);
    }

    private function fieldsetNumberSettings(): Schemas\Components\Fieldset
    {
        return
            Schemas\Components\Fieldset::make(__('mediamouse-users::pages/user-settings.number-settings'))
                ->columnSpan(1)
                ->columns(1)
                ->schema([
                    Forms\Components\Select::make('number_format')
                        ->inlineLabel()
                        ->label(__('mediamouse-users::pages/user-settings.number-notation'))
                        ->selectablePlaceholder(false)
                        ->options([
                            'global' => __('mediamouse-users::pages/user-settings.datetime-global-setting'),
                            'COMMA' => number_format(1234.56, 2, ',', ''),
                            'DOT' => number_format(1234.56, 2, '.', ''),
                            'COMMA_DOT' => number_format(1234.56, 2, ',', '.'),
                            'DOT_COMMA' => number_format(1234.56, 2, '.', ','),
                        ]),
                ]);
    }

    private function fieldsetCsvSettings(): Schemas\Components\Fieldset
    {
        return
            Schemas\Components\Fieldset::make(__('mediamouse-users::pages/user-settings.csv-settings'))
                ->columnSpan(1)
                ->columns(1)
                ->schema([
                    Forms\Components\Select::make('csv_delimiter')
                        ->inlineLabel()
                        ->label(__('mediamouse-users::pages/user-settings.csv-delimiter'))
                        ->selectablePlaceholder(false)
                        ->options([
                            'global' => __('mediamouse-users::pages/user-settings.datetime-global-setting'),
                            'COMMA' => __('mediamouse-users::pages/user-settings.csv-delimiter-comma'),
                            'SEMICOLON' => __('mediamouse-users::pages/user-settings.csv-delimiter-semicolon'),
                            'TAB' => __('mediamouse-users::pages/user-settings.csv-delimiter-tab'),
                        ]),
                    Forms\Components\Select::make('csv_enclosure')
                        ->inlineLabel()
                        ->label(__('mediamouse-users::pages/user-settings.csv-enclosure'))
                        ->selectablePlaceholder(false)
                        ->options([
                            'global' => __('mediamouse-users::pages/user-settings.datetime-global-setting'),
                            'single' => __('mediamouse-users::pages/user-settings.csv-enclosure-single'),
                            'double' => __('mediamouse-users::pages/user-settings.csv-enclosure-double'),
                        ]),
                    Forms\Components\Select::make('csv_new_line')
                        ->inlineLabel()
                        ->label(__('mediamouse-users::pages/user-settings.csv-new-line'))
                        ->selectablePlaceholder(false)
                        ->options([
                            'global' => __('mediamouse-users::pages/user-settings.datetime-global-setting'),
                            'R' => __('mediamouse-users::pages/user-settings.csv-new-line-r'),
                            'N' => __('mediamouse-users::pages/user-settings.csv-new-line-n'),
                            'RN' => __('mediamouse-users::pages/user-settings.csv-new-line-rn'),
                        ]),
                ]);
    }

    private function fieldsetDateSettings(): Schemas\Components\Fieldset
    {
        $date = Carbon::create(2023, 8, 1, 4, 35, 59);

        $date_formats = [
            'M jS, Y',
            'j M Y',
            'j F Y',
            'D j M Y',
            'D M jS, Y',
            'l j F Y',
            'j-n-Y',
            'd-m-Y',
            'm-d-Y',
            'n-j-Y',
            'Y-m-d',
        ];

        $time_formats = [
            'H:i:s',
            'h:i:s a',
            'h:i:s A',
            'G:i:s',
            'g:i:s a',
            'g:i:s A',
        ];

        $date_options = ['global' => __('mediamouse-users::pages/user-settings.datetime-global-setting')];

        $datetime_options = [
            'auto' => __('mediamouse-users::pages/global-settings.datetime-auto-setting'),
            'split' => __('mediamouse-users::pages/global-settings.datetime-split-setting'),
        ];
        $time_options = ['global' => __('mediamouse-users::pages/user-settings.datetime-global-setting')];
        foreach($date_formats as $date_format) {
            $date_options[$date_format] = $date->translatedFormat($date_format);
            foreach($time_formats as $time_format) {
                $datetime_format = $date_format . ' ' . $time_format;
                $datetime_options[$datetime_format] = $date->translatedFormat($datetime_format);
            }
        }

        foreach($time_formats as $time_format) {
            $time_options[$time_format] = $date->translatedFormat($time_format);
        }

        return
            Schemas\Components\Fieldset::make(__('mediamouse-users::pages/user-settings.date-settings'))
                    ->columnSpan(1)
                    ->columns(1)
                    ->schema([
                        Forms\Components\Select::make('date_format')
                            ->inlineLabel()
                            ->selectablePlaceholder(false)
                            ->label(__('mediamouse-users::pages/user-settings.date-notation'))
                            ->options($date_options),
                        Forms\Components\Select::make('time_format')
                            ->inlineLabel()
                            ->selectablePlaceholder(false)
                            ->label(__('mediamouse-users::pages/user-settings.time-notation'))
                            ->options($time_options),
                        Forms\Components\Select::make('dateTime_format')
                            ->inlineLabel()
                            ->selectablePlaceholder(false)
                            ->label(__('mediamouse-users::pages/user-settings.datetime-notation'))
                            ->options($datetime_options),
                ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Schemas\Components\Grid::make(2)->schema([
                Schemas\Components\Grid::make(1)->columnSpan(1)->schema([
                        $this->fieldsetDetailsSettings(),
                        $this->fieldsetLanguageSettings(),
                        $this->fieldsetNumberSettings(),
                    ]),
                Schemas\Components\Grid::make(1)->columnSpan(1)->schema([
                        $this->fieldsetDateSettings(),
                        $this->fieldsetCsvSettings(),
                    ]),
                ])
        ];
    }


//    protected function getForms(): array
//    {
//        return [
//            'form' => $this->form()
//                ->schema($this->getFormSchema())
//                ->statePath('data')
//                ->columns(2)
//                ->inlineLabel(config('filament.layout.forms.have_inline_labels')),
//        ];
//    }

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

        $refreshPage = $current_user->language_iso != $data['language_iso'];

        $current_user->name = $data['name'];
        $current_user->email = $data['email'];
        $current_user->language_iso = $data['language_iso'];

        $current_user->save();

        $current_user->updateUserSetting('mediamouse-users.date_format', $data['date_format']);
        $current_user->updateUserSetting('mediamouse-users.time_format', $data['time_format']);
        $current_user->updateUserSetting('mediamouse-users.dateTime_format', $data['dateTime_format']);
        $current_user->updateUserSetting('mediamouse-users.number_format', $data['number_format']);
        $current_user->updateUserSetting('mediamouse-users.csv_delimiter', $data['csv_delimiter']);
        $current_user->updateUserSetting('mediamouse-users.csv_enclosure', $data['csv_enclosure']);
        $current_user->updateUserSetting('mediamouse-users.csv_new_line', $data['csv_new_line']);

        Notification::make()
            ->success()
            ->title(__('mediamouse-users::pages/user-settings.user-updated'))
            ->send();

        if($refreshPage) {
            response()->redirectTo(UserSettings::getUrl());
        }
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('filament-spatie-laravel-settings-plugin::pages/settings-page.form.actions.save.label'))
            ->submit('submit')
            ->keyBindings(['mod+s']);
    }

    public function getFormContentComponent(): Component
    {
        return \Filament\Schemas\Components\Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler('save')
            ->footer([
                $this->getFormActionsContentComponent(),
            ]);
    }

    public function getFormActionsContentComponent(): Component
    {
        return Actions::make($this->getFormActions())
            ->alignment($this->getFormActionsAlignment())
            ->fullWidth($this->hasFullWidthFormActions())
            ->sticky($this->areFormActionsSticky());
    }

    public function getSubmitFormAction(): Action
    {
        return $this->getSaveFormAction();
    }

    protected function getSubmitFormLivewireMethodName(): string
    {
        return 'save';
    }

    public function hasFormWrapper(): bool
    {
        return true;
    }

    protected function hasFullWidthFormActions(): bool
    {
        return false;
    }

    public function getRedirectUrl(): ?string
    {
        return null;
    }

    public function canEdit(): bool
    {
        return true;
    }
}

