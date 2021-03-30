<div>

     {{-- alerts --}}
    @if (session()->has('message'))
        @php
            $type = session('messageType');              
            $message = session('message');
            $alertType = isset($type) ? $type : 'success';
        @endphp

        <div x-show="open" x-data="{ open: true }" class="fixed z-50 left-0 w-full p-4 md:w-1/2 md:top-0 md:right-0 md:p-8 md:left-auto xl:w-1/3">
            <div class="{{ variants()->alert($alertType)->class('base') }} rounded p-4 flex items-center shadow-lg">
                <div class="{{ variants()->alert($alertType)->class('icon') }} mr-4 rounded-full p-2">
                    <div class="{{ variants()->alert($alertType)->class('base') }} rounded-full p-1 border-2">
                        <i data-feather="{{ variants()->alert($alertType)->icon() }}" class="text-sm w-4 h-4 font-semibold"></i>
                    </div>
                </div>

                <div class="flex-1">
                    <b class="{{ variants()->alert($alertType)->title('base') }} font-semibold">
                        {{ variants()->alert($alertType)->title() }}!
                    </b>
                    <p class="text-sm">{{ $message }}</p>
                </div>
                <a href="#" x-on:click="open = false" wire:click="resetMessages">
                    <i data-feather="x-circle"></i>
                </a>
            </div>
        </div>
    @endif

    {{-- confirmation --}}
    @if (session()->has('confirmationMessage'))
        <div class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>
            <div class="bg-white rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <i data-feather="alert-triangle" class="text-red-600"></i>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <div class="mt-2">
                    {{ session('confirmationMessage') }}
                    </div>
                    <span wire:loading class="mr-4">
                    Executing action
                    </span>
                </div>
                </div>
                <div wire:loading.remove class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <span class="flex w-full sm sm:ml-3 sm:w-auto">
                    @component('laravel-views::components.button', [
                    'variant' => 'danger',
                    'title' => "Yes, I'm sure",
                    'onWireClick' => "delete",
                    'block' => true
                    ])
                    @endcomponent
                </span>
                <span class="mt-3 flex w-full sm:mt-0 sm:w-auto">
                    @component('laravel-views::components.button', [
                    'variant' => 'light',
                    'title' => 'Cancel',
                    'onWireClick' => 'cancelDelete',
                    ])
                    @endcomponent
                </span>
                </div>
            </div>
        </div>
    @endif

    <!-- Editor -->
    <div x-data="{ editMode: {{ $isEditMode }}, tab: '{{ $tab }}', isPending: {{ $isPending }} }">

        <!-- Header -->
        <div class="lg:flex lg:items-center lg:justify-between py-3">

            <!-- Header -->
            <div class="flex-1 min-w-0">        
                <h4>
                    <input wire:model.defer="filename" x-bind:disabled="!editMode" x-bind:class="{ 
                            'text-gray-600 bg-gray-100' : !editMode, 
                            'border' : editMode 
                        }" class="text-xl font-semibold w-full focus:border-gray-500 focus:bg-white focus:ring-0" />
                </h4>
                
                <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6 text-gray-600">
                    <div class="mt-2 flex items-center text-sm">
                        <!-- feather icon name: info -->
                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        {{ __('Status') }}: <?php echo $badgeHtml ?>
                    </div>
                    <div class="mt-2 flex items-center text-sm">
                        <!-- Heroicon name: paper clip -->
                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        {{ __('File type') }}: {{ $document->file_type }}
                    </div>
                    <div class="mt-2 flex items-center text-sm">
                        <!-- Heroicon name: hash -->
                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                        </svg>
                        {{ __('File size') }}: {{ $document->file_size }}
                    </div>
                    <div class="mt-2 flex items-center text-sm">
                        <!-- Heroicon name: calendar -->
                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ __('Created') }}: {{ $document->created_at }}
                    </div>
                    <div class="mt-2 flex items-center text-sm">
                        <!-- Heroicon name: calendar -->
                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ __('Updated') }}: {{ $document->updated_at }}
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-5 flex lg:mt-0 lg:ml-4">
                
                <span x-show="!editMode" class="hidden sm:block ml-3">
                    <button type="button" wire:click="confirmDelete" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <!-- Heroicon name: delete -->
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        {{ __('Delete') }}
                    </button>
                </span>

                <span x-show="!editMode" class="sm:ml-3">
                    <button type="button" x-bind:disabled="isPending == 1" x-on:click="editMode = 1" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium focus:outline-none"
                        x-bind:class=" {
                            'text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500' : !isPending,
                            'text-gray-500 bg-gray-300 cursor-not-allowed' : isPending
                        } ">
                        <!-- feather icon name: edit -->
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" x-bind:class="{ 'text-white feather feather-edit' : !isPending, 'text-gray-500' : isPending }">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        {{ __('Edit') }}
                    </button>
                </span>

                <span x-show="editMode" class="hidden sm:block ml-3">
                    <button type="button" x-on:click="editMode = false" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <!-- Heroicon name: ban -->
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                        {{ __('Cancel') }}
                    </button>
                </span>

                <span x-show="editMode" class="sm:ml-3">
                    <button type="button" wire:click="save" x-on:click="editMode = false" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <!-- feather icon name: save -->
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        {{ __('Save') }}
                    </button>
                </span>

            </div>
        </div>

        <!-- Tabs -->
        <ul class="list-reset flex border-b">
            <li x-show="tab === 'file-viewer'" class="-mb-px mr-1">
                <a class="bg-white inline-block border-l border-t border-r rounded-t py-2 px-4 text-indigo-500 font-semibold">
                    {{ __('File Viewer') }}
                </a>
            </li>
            <li x-show="tab !== 'file-viewer'" class="mr-1">
                <a x-on:click="tab = 'file-viewer'" class="bg-gray-100 inline-block py-2 px-4 hover:bg-gray-200 font-semibold" href="#">
                    {{ __('File Viewer') }}
                </a>
            </li>
            <li x-show="tab === 'meta'" class="-mb-px mr-1">
                <a class="bg-white inline-block border-l border-t border-r rounded-t py-2 px-4 text-indigo-500 font-semibold">
                    {{ __('Meta Data') }}
                </a>
            </li>
            <li x-show="tab !== 'meta'" class="mr-1">
                <a x-on:click="tab = 'meta'" class="bg-gray-100 inline-block py-2 px-4 hover:bg-gray-200 font-semibold" href="#">
                    {{ __('Meta Data') }}
                </a>
            </li>
        </ul>

        <!-- PDF View -->
        <div x-show="tab === 'file-viewer'" class="bg-white shadow-xl sm:rounded-lg md:flex-row">

            @if( ! $isPending ) 
                <div id="pdf">
                    <div x-data="{ hasData: false }" x-data class="col-span-6 sm:col-span-6">
                        <!-- file upload -->
                        <div class="p-6">
                            <p class="flex justify-center"><strong>PDF file not found.</strong> Want to upload one?</p>
                            <x-jet-label for="editorFileinput" value="{{ __('File') }}" class="sr-only" />
                            <div x-on:click="document.getElementById('editorFileinput').click();" class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer">
                                <div class="space-y-1 text-center">
                                
                                    <!-- Heroicon name: upload (medium) -->
                                    <svg x-show="!hasData" class="mx-auto h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">                                        
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    <!-- Heroicon name: paper clip (medium) -->
                                    <svg x-show="hasData" class="mx-auto h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    
                                    <input x-on:change="$wire.set('editorFileInputName', document.getElementById('editorFileinput').value.split('\\').reverse()[0]); hasData = true;" id="editorFileinput" type="file" wire:model.defer="editorFileinput" class="sr-only" accept=".pdf" />
                                    <x-jet-input id="editorFileInputName" type="text" wire:model.defer="editorFileInputName" class="sr-only" />
                                    <div class="flex text-sm text-gray-600">
                                        <span x-show="!hasData" class="relative bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            {{ __('Upload a file') }}
                                        </span>
                                        <span x-show="hasData" x-text="$wire.get('editorFileInputName');" class="max-w-3/4 relative bg-white rounded-md font-medium text-gray-600 "></span>
                                    </div>
                                    <p x-show="!hasData" class="text-xs text-gray-500">
                                        {{ __('PDF up to 10MB') }}
                                    </p>
                                </div>
                            </div>
                            <x-jet-input-error for="editorFileinput" class="mt-2" />
                            <div class="flex justify-end py-2">
                            <x-jet-button wire:click="addFile" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('Upload') }}
                            </x-jet-button>
                            </div>
                        </div>
                    </div> 
                </div>

                @if( ! empty($document->path))
                    <script>
                    
                        function doesFileExist(urlToFile) {
                            var xhr = new XMLHttpRequest();
                            xhr.open('HEAD', urlToFile, false);
                            xhr.send();
                            
                            if (xhr.status == "404") {
                                return false;
                            } else {
                                return true;
                            }
                        }

                        if (doesFileExist("{{ '/files/' . $document->path }}")) {
                            
                            var options = {
                                height: screen.height + "px",
                                pdfOpenParams: { 
                                    view: 'FitH',
                                    pagemode: 'thumbs'
                                }
                            };

                            //insert the PDF viewer in container with CSS selector "#pdf"
                            PDFObject.embed("{{ '/files/' . $document->path }}", "#pdf", options);
                        }
                        
                    </script>
                @endif

            @else
                <div x-show="isPending" class=" p-4 flex justify-center">
                    <span class="py-16 block font-medium text-sm text-gray-700 block text-sm font-bold text-gray-700">
                        {{ __('OCR scan in progress') }}
                    </span>
                </div>
            @endif

        </div>

        <!-- Meta Data -->
        <div x-show="tab === 'meta'" class="bg-white shadow-xl sm:rounded-lg md:flex-row p-4">
            <div>
              <label for="tags" class="block font-medium text-sm text-gray-700 block text-sm font-bold text-gray-700">
                {{ __('Tags') }}
              </label>
              <div class="mt-1" x-show="editMode">
                <input type="text" wire:model.defer="editorTags" x-bind:disabled="!editMode" class="border form-input rounded-md shadow-sm placeholder-gray-400 form-input mt-1 block w-full focus:border-gray-500 focus:bg-white focus:ring-0" />
              </div>
              <div class="mt-1" x-show="!editMode">
                <?php echo $tagsHtml ?>
              </div>
              <p class="mt-2 text-sm text-gray-500">
                {{ __('A list of associated tags with this document.') }}
              </p>
            </div>
            <div>
              <label for="content" class="block font-medium text-sm text-gray-700 block text-sm font-bold text-gray-700">
                {{ __('Content') }}
              </label>
              <div class="mt-1">
                <textarea wire:model.defer="content" x-bind:disabled="!editMode" x-bind:class="{ 
                        'text-gray-600 bg-gray-100' : !editMode, 
                        'border' : editMode 
                    }" rows="12" class="form-input rounded-md shadow-sm placeholder-gray-400 form-input mt-1 block w-full focus:border-gray-500 focus:bg-white focus:ring-0"></textarea>
              </div>
              <p class="mt-2 text-sm text-gray-500">
                {{ __('A textual representation of this document that is used in the fulltext search.') }}
              </p>
            </div>
        </div>
        
    </div>
</div>
