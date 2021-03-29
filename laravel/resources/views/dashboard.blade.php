<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold leading-7 text-gray-900 sm:text-xl sm:truncate">
            {{ __('Overview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex flex-col md:flex-row">
                
                    <div class="container w-3/4 px-4 py-4">
                        <h3>{{ __('These documents need your attention:') }}</h3>
                        <div>
                            <livewire:documents.attention-overview-table />
                        </div>
                    </div>
                    <div class="container w-1/4 px-4 py-4 float-right text-gray-600 bg-gray-200">
                        <livewire:documents.stats />
                    </div>
                
            </div>
        </div>
        
    </div>

</x-app-layout>
