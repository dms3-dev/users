<div>
    <form wire:submit.prevent="forgotPassword" class="space-y-8">
        {{ $this->form }}

        <x-filament::button type="submit" form="authenticate" class="w-full">
            {{ __('mediamouse-users::pages/login.send-reset-password-link') }}
        </x-filament::button>
    </form>
    <div class="mt-1 text-center">
        <a wire:click="returnToLogin" class="text-primary-500 cursor-pointer">{{ __('mediamouse-users::pages/login.return-to-login') }}</a>
    </div>
</div>
