<div>
    <h3 class="font-bold text-xl leading-tight">{{ __('Stats') }}</h3>


    <div class="flex-col">
        
        <div class="py-4">
            <h4 class="font-bold">{{ __('# Total') }}:</h4>
            <ul class="px-4 py-2">
                <li>
                    <a href="{{ route('document.list') }}">
                        {{ $total }}
                        {{ __('Documents') }}
                    </a>
                </li>
            </ul>
        </div>

    </div>

    <div class="flex-col">
        
        <div class="py-4">
            <h4 class="font-bold">{{ __('# Documents by Status') }}:</h4>
            <ul class="px-4 py-2">
                @foreach ($stats as $item)
                    <li>
                        <a href="{{ route('document.list', ['filters[status-filter]' => $item->type ]) }}">
                            {{ $item->value }}  
                            <?php echo App\Util\DocumentHelper::getStatusBadge($item->type); ?>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

    </div>

</div>
