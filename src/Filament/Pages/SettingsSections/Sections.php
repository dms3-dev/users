<?php

namespace Mediamouse\Users\Filament\Pages\SettingsSections;

class Sections {
    public static array $sections = [];

    public static function add($className): void
    {
        if(!in_array($className, self::$sections)) self::$sections[] = $className;
    }

    public static function getSections(): array
    {
        $sections = [];
        foreach(self::$sections as $section) {
            $sections[] = $section::section();
        }

        return $sections;
    }



}
