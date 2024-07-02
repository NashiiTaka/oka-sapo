<x-layouts.public title="おすすめ商品♪ | コスメ★ピシャット">
  <div class="container mx-auto py-6">
    <div class="overflow-x-auto">
      <div class="flex space-x-4">
        @foreach ($recommendations as $recommendation)
          <div class="min-w-[250px] bg-white p-4 border rounded shadow">
            <p class="text-base font-bold">{{ $recommendation->product_name }}</p>
            <img src="{{ $recommendation->valiations[0]->getImagePath() }}"
              alt="{{ $recommendation->valiations[0]->valiation_name }}">
            <p class="text-xs font-semibold">{{ $recommendation->maker->maker_name }}</p>
            <p class="text-xs font-semibold">{{ $recommendation->brand->brand_name }}</p>
            <p class="text-xl font-bold">{{ $recommendation->price_with_tax }}</p>
            <div class="container mx-auto py-6">
              <div class="overflow-x-auto">
                <div class="flex space-x-4">
                  @foreach ($recommendation->valiations as $valiation)
                    <div class="w-10 h-10 rounded-full" style="background-color: {{ $valiation->hex_color_code }}">
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
  <div class="p-5">
    <x-a-button href="/chat/shitukan" display="詳しい条件を指定" />
  </div>
</x-layouts.public>
