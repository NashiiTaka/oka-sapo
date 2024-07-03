<x-layouts.public title="おすすめ商品♪ | コスメ★ピシャット">
  <script src="/js/favorites.js"></script>
  <h1 class="text-lg mt-4 mx-4" id="animated-message"></h1>
  <div class="container mx-auto pt-3 pb-5">
    <div class="overflow-x-auto">
      <div class="flex space-x-4 px-3">
        @foreach ($recommendations as $recommendation)
          <div class="min-w-[270px] bg-white p-4 border rounded-xl shadow flex flex-col items-center gap-y-2">
            <div class="h-12 flex items-center">
              <p class="text-base font-bold">{{ $recommendation->product_name }}</p>
            </div>
            <div class="h-28 flex items-center justify-center">
              <img class="max-h-28" src="{{ $recommendation->getImagePath() }}"
                alt="{{ $recommendation->valiations[0]->valiation_name }}">
            </div>
            <p class="text-xs font-semibold">{{ $recommendation->maker->maker_name }} :
              {{ $recommendation->brand->brand_name }}</p>
            <p class="text-xl font-bold">{{ number_format($recommendation->price_with_tax) . '円(税込)' }}</p>
            <div class="container w-full py-1">
              <div class="overflow-x-auto">
                <div class="flex space-x-4">
                  @foreach ($recommendation->valiations as $valiation)
                    <div class="min-w-10 min-h-10 rounded-full"
                      style="background-color: {{ $valiation->hex_color_code }}">
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
            <div class="flex flex-row gap-x-3 items-center">
              @if ($recommendation->buy_url)
                <x-a-button href="{{ $recommendation->buy_url }}" display="購入" target="_blank" px="6"
                  py="2" />
                <x-a-button href="/face-detection/{{ $recommendation->product_id }}" display="試着" target="_blank"
                  px="6" py="2" />
              @endif
              <img class="h-7 w-7 favorites" src="/img/favorite-off.png" data-product-id="{{ $recommendation->product_id }}" />
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
