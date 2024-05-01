<div>
    <form wire:submit.prevent="submit" class="space-y-8">
        {{ $this->form }}

        <x-filament::button type="submit" form="submit" class="w-full">
            {{ __('mediamouse-users::pages/login.change-password') }}
        </x-filament::button>
    </form>
    @if(!$isLoggedIn)
    <div class="mt-1 text-center">
        <a wire:click="returnToLogin" class="text-primary-500 cursor-pointer">{{ __('mediamouse-users::pages/login.return-to-login') }}</a>
    </div>
    @endif
</div>
