<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold leading-7 text-gray-900 sm:text-xl sm:truncate">
            {{ __('Document Search') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg md:flex-row">
                
                <livewire:documents.search-result-table />
                
            </div>
        </div>
        
    </div>

</x-app-layout>