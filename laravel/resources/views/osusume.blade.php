{{ $name }}<br>
{{ $age }}<br>

@foreach ($recommendations as $recommendation)
    {{ $recommendation->product_name }}<br>
    {{ $recommendation->product_id }}<br>
@endforeach