@props(['action', 'method' => 'DELETE', 'message' => 'Are you sure?', 'button' => 'Delete', 'variant' => 'danger'])

<form method="POST" action="{{ $action }}" onsubmit="return confirm('{{ $message }}')">
    @csrf
    @method($method)
    <x-ui.button type="submit" :variant="$variant" size="sm">
        {{ $button }}
    </x-ui.button>
</form>
