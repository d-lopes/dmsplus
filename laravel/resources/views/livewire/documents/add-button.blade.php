<div>
    <!-- the button -->
    <div class="px-4">
        <button type="button" wire:click="showDialog" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <!-- Heroicon name: document-add (small) -->
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd" />
            </svg>
            {{ __('Add Document') }}
        </button>
    </div>

    <!-- the dialog -->
    <x-jet-dialog-modal id="createDocumentDialog" wire:model="dialog" maxWidth="2xl">
        <x-slot name="title"> 
            <div class="float-left mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                <!-- Heroicon name: document-add (medium) -->
                <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="px-12 py-2 text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                {{ __('Add Document') }}
            </h3>
        </x-slot>
        <x-slot name="content">
            <!-- form content -->
            <x-jet-form-section submit="save">
                <x-slot name="title">
                    {{ __('File Upload') }}
                </x-slot>
                <x-slot name="description">
                    {{ __('Please upload a file for the new document that you want to create. The file name is taken over from the uploaded file, but can also be overwritten afterwards.') }}
                </x-slot>
                <x-slot name="form">
                    <div x-data="{ hasData: false }" @click.away="hasData = false;" x-data class="col-span-6 sm:col-span-6">
                        <!-- file upload -->
                        <div class="py-2">
                            <x-jet-label for="file" value="{{ __('File') }}" class="block text-sm font-bold text-gray-700" />
                            <div x-on:click="document.getElementById('fileinput').click();" class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer">
                                <div class="space-y-1 text-center">
                                
                                    <!-- Heroicon name: upload (medium) -->
                                    <svg x-show="!hasData" class="mx-auto h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">                                        
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    <!-- Heroicon name: paper clip (medium) -->
                                    <svg x-show="hasData" class="mx-auto h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    
                                    <input x-on:change="$wire.set('filename', document.getElementById('fileinput').value.split('\\').reverse()[0]); hasData = true;" id="fileinput" type="file" wire:model.defer="fileinput" class="sr-only" accept=".pdf" />
                                    <div class="flex text-sm text-gray-600">
                                        <span x-show="!hasData" class="relative bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            {{ __('Upload a file') }}
                                        </span>
                                        <span x-show="hasData" x-text="document.getElementById('fileinput').value.split('\\').reverse()[0];" class="max-w-3/4 relative bg-white rounded-md font-medium text-gray-600 "></span>
                                    </div>
                                    <p x-show="!hasData" class="text-xs text-gray-500">
                                        {{ __('PDF up to 10MB') }}
                                    </p>
                                </div>
                            </div>
                            <x-jet-input-error for="fileinput" class="mt-2" />
                        </div>
                        <!-- file name -->
                        <div class="py-2">
                            <x-jet-label for="filename" value="{{ __('File name') }}" class="block text-sm font-bold text-gray-700" />
                            <x-jet-input id="filename" type="text" wire:model.defer="filename" placeholder="file.pdf" class="placeholder-gray-400 form-input mt-1 block w-full focus:border-gray-500 focus:bg-white focus:ring-0" />
                            <x-jet-input-error for="filename" class="mt-2" />
                        </div>
                    </div>
                </x-slot>
                <x-slot name="actions">
                    <x-jet-secondary-button wire:click="closeDialog" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Cancel') }}
                    </x-jet-secondary-button>
                    <x-jet-button class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Save') }}
                    </x-jet-button>
                </x-slot>
            </x-jet-form-section>
        </x-slot>
        <x-slot name="footer"></x-slot>
    </x-jet-dialog-modal>
</div>