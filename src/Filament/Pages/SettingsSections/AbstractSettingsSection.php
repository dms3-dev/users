<?php

namespace Mediamouse\Users\Filament\Pages\SettingsSections;

use Filament\Forms\Components\Section;
use Illuminate\Support\Str;

abstract class AbstractSettingsSection
{
    protected static string $settings;

    public abstract static function section() : Section;

    public static function save(array $data) : bool {

        $settings = app(static::getSettings());

        $settings->fill($data);
        $settings->save();

        return true;
    }

    public static function data() : array {
        return app(static::getSettings())->toArray();
    }

    //@todo improve this settings
    public static function getSettings(): string
    {
        return static::$settings;
//        return static::$settings ?? (string) Str::of(class_basename(static::class))
//            ->beforeLast('Settings')
//            ->prepend('App\\Settings\\')
//            ->append('Settings');
    }

}
