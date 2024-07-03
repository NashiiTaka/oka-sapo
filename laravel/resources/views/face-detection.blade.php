<x-layouts.public title="色をためす！ | コスメ★ピシャット">
  <style>
    .color-palette {
      display: flex;
      flex-wrap: wrap;
      width: 100%;
      justify-content: center;
      margin-top: 20px;
    }

    .color-swatch {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      margin: 5px;
      cursor: pointer;
    }

    .selected {
      border: 2px solid blue;
    }
  </style>
  <div class="relative">
    <video id="video" class="w-full max-h-72 absolute" autoplay muted></video>
    <canvas id="overlay" class="w-full max-h-72 absolute"></canvas>
  </div>
  <div class="color-palette" id="colorPalette"></div>
  <div class="p-2">
    @if (isset($product) && $product->buy_url)
      <p class="font-bold">{{ $product->product_name }}</p>
      <x-a-button href="{{ $product->buy_url }}" display="購入" target="_blank" />
    @else
      <x-a-button href="/osusume/" display="商品を検索" id="btn-seach" />
    @endif
  </div>
  <script>
    {!! $colors !!}

    window.addEventListener('load', () => {
      const video = document.getElementById('video');
      video.addEventListener('loadedmetadata', () => {
        console.log('Metadata loaded');
        const elem1 = document.getElementById('video');
        // const elem2 = document.getElementById('overlay');

        const elem1Height = elem1.offsetHeight;
        // elem2.style.height = `${elem1Height}px`;

        const colors = document.getElementById('colorPalette');
        colors.style.marginTop = `${elem1Height + 10}px`;
      });

      document.getElementById('btn-seach').addEventListener('click', (event) => {
        event.preventDefault();
        const selectedColor = document.querySelector('.selected');
        window.location.href = `/osusume/face-detection/${encodeURIComponent(selectedColor.hexColor)}`;
      });
    });
  </script>
  <script defer src="/js/face-api.js"></script>
  <script defer src="/js/face-detection.js"></script>
</x-layouts.public>
