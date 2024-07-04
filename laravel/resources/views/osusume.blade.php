<x-layouts.public title="おすすめ商品♪ | コスメ★ピシャット">
  <script src="/js/local-storage.js"></script>
  <h1 class="text-lg mt-4 mx-4" id="animated-message"></h1>
  <div class="container mx-auto pt-3 pb-5">
    <div class="overflow-x-auto">
      <div class="flex space-x-4 px-3">
        @if (!isset($products) || empty($products) || count($products) === 0)
          <p>商品が見つかりませんでした。</p>
        @endif
        @foreach ($products as $product)
          <div class="min-w-[270px] bg-white p-4 border rounded-xl shadow flex flex-col items-center gap-y-2">
            <div class="h-12 flex items-center">
              <p class="text-base font-bold">{{ $product->product_name }}</p>
            </div>
            <div class="h-28 flex items-center justify-center">
              <img class="max-h-28" src="{{ $product->getImagePath() }}"
                alt="{{ $product->product_name }}">
            </div>
            <p class="text-xs font-semibold">{{ $product->maker->maker_name }} :
              {{ $product->brand->brand_name }}</p>
            <p class="text-xl font-bold">{{ number_format($product->price_with_tax) . '円(税込)' }}</p>
            <div class="container w-full py-1">
              <div class="overflow-x-auto">
                <div class="flex space-x-4">
                  @foreach ($product->valiations as $valiation)
                    <div class="min-w-10 min-h-10 rounded-full"
                      style="background-color: {{ $valiation->hex_color_code }}">
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
            <div class="flex flex-row gap-x-3 items-center">
              @if ($product->buy_url && strpos($product->buy_url, 'http') === 0)
                <x-a-button href="{{ $product->buy_url }}" display="購入" target="_blank" px="6" py="2" />
              @endif
              @if($product->product_id)
                <x-a-button href="/face-detection/{{ $product->product_id }}" display="試着" px="6" py="2" />
              @endif
              <img class="h-7 w-7 favorites" src="/img/favorite-off.png"
                data-product-id="{{ $product->product_id }}" />
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
  <div>
    <x-a-button href="/chat/shitukan" display="もっと他の商品もさがしてみる" />
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      initFavorites('.favorites');

      const message = `{!! nl2br(e($message)) !!}`;
      const messageContainer = document.getElementById('animated-message');
      let i = 0;

      function typeWriter() {
        if (i < message.length) {
          messageContainer.innerHTML += message.charAt(i);
          i++;
          setTimeout(typeWriter, 50);
        }
      }

      typeWriter();
    });
  </script>
</x-layouts.public>
