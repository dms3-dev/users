<?php

use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

Route::name('mediamouse-users.')
    ->middleware([
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        AuthenticateSession::class,
        ShareErrorsFromSession::class,
        VerifyCsrfToken::class,
        SubstituteBindings::class,
        DisableBladeIconComponents::class,
        DispatchServingFilamentEvent::class,
    ])
    ->prefix('/admin')
    ->group(function () {
        Route::get('/2fa', \Mediamouse\Users\Http\Livewire\Auth\Challenge::class)->name('auth.2fa');
        Route::get('/forgot-password', \Mediamouse\Users\Http\Livewire\Auth\ForgotPassword::class)->name('auth.forgot-password');
        Route::get('/reset-password/{token}', \Mediamouse\Users\Http\Livewire\Auth\EnterNewPassword::class)->name('auth.reset-password');
        Route::get('/reset-password', \Mediamouse\Users\Http\Livewire\Auth\EnterNewPassword::class)->name('auth.change-password');
    });

