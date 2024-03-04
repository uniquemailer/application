<x-filament-panels::page>
    <div class="w-full p-4">
        <form wire:submit="generateApiKey" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 p-4">

            <div class="mb-4">
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="key" type="text" placeholder="*************************************" value="{{$key}}">
            </div>
            <div class="flex items-center justify-between">
                <x-filament::button type="submit">
                    Generate New API Key
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>