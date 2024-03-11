<x-filament-panels::page>
    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}

        <x-filament::button type="submit">
            Run Selected Actions
        </x-filament::button>

    </form>
</x-filament-panels::page>