<div>
    <form wire:submit.prevent="resetPassword" class="space-y-8">
        {{ $this->form }}

        <x-filament::button type="submit" form="authenticate" class="w-full">
            Change password
        </x-filament::button>
    </form>
    @if(!$isLoggedIn)
    <div class="mt-1 text-center">
        <a wire:click="returnToLogin" class="text-primary-500 cursor-pointer">Return to login</a>
    </div>
    @endif
</div>
