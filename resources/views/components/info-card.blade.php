@props(['title' => null, 'motHistory' => []])

<div class="max-w-3xl mx-auto bg-gray-800 shadow-xl rounded-2xl p-6 mb-6">
    @isset($title)
        <h2 class="text-2xl font-semibold mb-4 text-blue-400">{{ $title }}</h2>
    @endisset

    <ul class="space-y-2 text-gray-200">
        @foreach($motHistory as $label => $value)
            <li>
                <span class="font-semibold">{{ $label }}:</span> {{ $value }}
            </li>
        @endforeach
    </ul>
</div>
