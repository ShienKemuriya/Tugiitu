@props(['message', 'type'])

@if ($message)
    @php
        if ($type === 'error') {
            $classes = 'border px-4 py-3 rounded relative bg-red-100 border-red-400 text-red-700';
        } else {
            $classes = 'border px-4 py-3 rounded relative bg-green-100 border-green-400 text-green-700';
        }
    @endphp

    @if (is_array($message))
        @foreach ($message as $msg)
            <div class="{{ $classes }}">
                {{ $msg }}
            </div>
        @endforeach
    @else
        <div class="{{ $classes }}">
            {{ $message }}
        </div>
    @endif
@endif