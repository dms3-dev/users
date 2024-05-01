<div x-data="{ open: true }">
    @if(strlen($message) > 0)
        <div x-show="open" class="mb-2 text-danger-500">
            {{ $message }}
        </div>
    @endif
    <form wire:submit.prevent="submit" class="space-y-8">
        {{ $this->form }}

        <x-filament::button type="submit" form="submit" class="w-full">
            {{ __('filament::login.buttons.submit.label') }}
        </x-filament::button>
    </form>
    <div class="mt-1 text-center">
        <a wire:click="requestNewCode" class="text-primary-500 cursor-pointer" x-on:click="open = true; setTimeout(() => { open = false; }, 5000)">Request a new code</a> or
        <a wire:click="returnToLogin" class="text-primary-500 cursor-pointer">return to login</a>
    </div>

</div>
