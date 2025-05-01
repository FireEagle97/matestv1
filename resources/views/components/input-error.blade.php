@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-danger mt-2 mb-0 p-0 small']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif 