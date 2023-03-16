<?php

use Illuminate\Support\Facades\Route;

Route::domain(config('filament.domain'))
    ->middleware(config('filament.middleware.base'))
    ->prefix(config('filament.path'))
    ->name('mediamouse-users.')
    ->group(function () {
        Route::any('/2fa', \Mediamouse\Users\Http\Livewire\Auth\Challenge::class)->name('auth.2fa');
    });
