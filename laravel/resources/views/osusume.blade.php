@foreach ($recommendations as $recommendation)
  <h1 class="text-3xl">{{ $recommendation->maker->maker_name }}</h1>
    ブランド名: {{ $recommendation->brand->brand_name }}<br>
    商品名: {{ $recommendation->product_name }}<br>
    税込価格: {{ $recommendation->price_with_tax }}<br>
  @foreach ($recommendation->valiations as $valiation)
    <img src="{{ $valiation->getImagePath() }}" alt="{{ $valiation->valiation_name }}">
    <div style="background-color: {{ $valiation->hex_color_code }}">{{ $valiation->valiation_name }}</div>
  @endforeach
@endforeach
