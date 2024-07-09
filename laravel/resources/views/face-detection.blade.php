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
  <div class="relative flex justify-center">
    <video id="video" class="absolute max-w-full" autoplay muted playsinline></video>
    <canvas id="overlay" class="absolute max-w-full"></canvas>
  </div>
  <div class="color-palette" id="colorPalette" style="visibility: hidden"></div>
  <div class="p-2">
    @if (isset($product) && $product->buy_url)
      <p class="font-bold">{{ $product->product_name }}</p>
      <x-a-button href="{{ $product->buy_url }}" display="購入" />
    @else
      <x-a-button href="/osusume/" display="商品を検索" id="btn-seach" />
    @endif
  </div>
  <script>
    {!! $colors !!}

    window.addEventListener('load', () => {
      const video = document.getElementById('video');
      video.addEventListener('loadedmetadata', () => {
        const colors = document.getElementById('colorPalette');
        colors.style.marginTop = `${video.offsetHeight + 10}px`;

        const overlay = document.getElementById('overlay');

        overlay.width = video.videoWidth;
        overlay.height = video.videoHeight;
        overlay.style.height = `${video.offsetHeight}px`;
        overlay.style.width = `${video.offsetWidth}px`;

        colors.style.visibility = 'visible';
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
