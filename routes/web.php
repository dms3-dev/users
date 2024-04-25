<?php

use Illuminate\Support\Facades\Route;

Route::domain(config('filament.domain'))
    ->middleware(config('filament.middleware.base'))
    ->prefix(config('filament.path'))
    ->name('mediamouse-users.')
    ->group(function () {
        Route::any('/2fa', \Mediamouse\Users\Http\Livewire\Auth\Challenge::class)->name('auth.2fa');
        Route::any('/forgot-password', \Mediamouse\Users\Http\Livewire\Auth\ForgotPassword::class)->name('auth.forgot-password');
        Route::any('/reset-password/{token}', \Mediamouse\Users\Http\Livewire\Auth\EnterNewPassword::class)->name('auth.reset-password');
        Route::any('/reset-password', \Mediamouse\Users\Http\Livewire\Auth\EnterNewPassword::class)->name('auth.reset-password');
    });


Route::permanentRedirect('/my-login', '/admin')->name('login');

Route::get('/maintenance', function () {
    return view('mediamouse-users::maintenance');
});
