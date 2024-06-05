<?php

if(!function_exists('hasPrivilege')) {
    function hasPrivilege(string $policy, \Mediamouse\Users\Enums\PolicyPrivilege $privilege) : bool {
        /** @var \Mediamouse\Users\Models\User $user */
        $user = \Filament\Facades\Filament::auth()->user();

        dd(get_class_vars());

        return $user->hasPrivilege($policy, $privilege);
    }

}
