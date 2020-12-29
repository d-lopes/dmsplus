<div class="">
    <h3 class="font-bold text-xl leading-tight">{{ __('Stats') }}</h3>

    <div class="flex-col">
        
        <div class="py-4">
            <h4 class="font-bold">{{ __('# Documents by Status') }}:</h4>
            <ul class="px-4 py-4">
                @foreach ($stats as $item)
                    <li>
                        <a href="#">
                        @if ($item->type == App\Models\DocumentStatus::PUBLISHED) 
                            <span class="px-2 inline-flex text-sm leading-5 font-bold rounded-full bg-gray-200 hover:bg-white hover:underline text-green-600 hover:text-green-500">{{ $item->value }} {{ __('Published') }}</span>
                        @elseif ($item->type == App\Models\DocumentStatus::INCOMPLETE)
                            <span class="px-2 inline-flex text-sm leading-5 font-bold rounded-full bg-gray-200 hover:bg-white hover:underline text-red-600 hover:text-red-500">{{ $item->value }} {{ __('Incomplete') }}</span>
                        @elseif ($item->type == App\Models\DocumentStatus::PENDING)
                            <span class="px-2 inline-flex text-sm leading-5 font-bold rounded-full bg-gray-200 hover:bg-white hover:underline text-orange-600 hover:text-orange-500">{{ $item->value }} {{ __('Pending') }}</span>
                        @elseif ($item->type == App\Models\DocumentStatus::CREATED)
                            <span class="px-2 inline-flex text-sm leading-5 font-bold rounded-full bg-gray-200 hover:bg-white hover:underline text-blue-600 hover:text-blue-500">{{ $item->value }} {{ __('Created') }}</span>
                        @else
                            <span class="px-2 inline-flex text-sm leading-5 font-bold rounded-full bg-gray-200 hover:bg-white hover:underline text-gray-600 hover:text-gray-500">{{ $item->value }} {{ __('Total') }}</span>
                        @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

    </div>

</div>
