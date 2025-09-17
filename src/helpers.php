<?php

if(!function_exists('hasPrivilege')) {
    function hasPrivilege(string $policy, \Mediamouse\Users\Enums\PolicyPrivilege $privilege) : bool {
        /** @var \Mediamouse\Users\Models\User $user */
        $user = \Filament\Facades\Filament::auth()->user();

        return $user?->hasPrivilege($policy, $privilege) ?? false;
    }

}

if(!function_exists('userCan')) {
    function userCan(mixed $policy, \Mediamouse\Laravel\Models\Model $record = null) {
        /** @var \Mediamouse\Users\Models\User $user */
        $user = \Filament\Facades\Filament::auth()->user();

        return $user?->canDo($policy, $record) ?? false;
    }
}


