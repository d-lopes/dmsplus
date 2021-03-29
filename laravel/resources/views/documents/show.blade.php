<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold leading-7 text-gray-900 sm:text-xl sm:truncate">
            {{ __('Document Detail') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <livewire:documents.editor :document="$document" />
            
        </div>
        
    </div>

</x-app-layout>