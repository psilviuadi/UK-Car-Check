@props(['data'])

<ul class="ml-4 border-l border-gray-700 pl-4 space-y-1">
    @foreach($data as $key => $value)
        <li>
            @if(is_array($value))
                <strong class="text-blue-400">{{ $key }}:</strong>
                <x-json-recursive :data="$value" />
            @else
                <span class="text-gray-300"><strong class="text-green-400">{{ $key }}:</strong> {{ $value }}</span>
            @endif
        </li>
    @endforeach
</ul>
