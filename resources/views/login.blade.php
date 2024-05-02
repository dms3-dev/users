<div>
    <h2 class="text-center mb-5 text-xl">{{ __('mediamouse-users::login.title') }}</h2>
    @if(strlen($message_text) > 0)
        <div class="mb-2 {{ $message_class }}">
            {{ $message_text }}
        </div>
    @endif
    <form wire:submit.prevent="authenticate" class="space-y-8">
        {{ $this->form }}

        <x-filament::button type="submit" form="authenticate" class="w-full">
            {{ __('mediamouse-users::login.buttons.submit.label') }}
        </x-filament::button>
    </form>
    <div class="mt-1 text-center">
        <a wire:click="forgotPassword" class="text-primary-500 cursor-pointer">{{ __('mediamouse-users::pages/login.forgot-password') }}</a>
    </div>
</div>
