<x-layouts.public title="シングルアンサー | コスメ★ピシャット">

  <div class="flex justify-center items-center mt-8">
    {{-- 女性のイラスト --}}
    <img src="/img/josei.png" alt="Home Icon" class="h-32 w-32">
  </div>

  <h1 class="text-lg mt-4 mb-8" id="animated-message "></h1>
  
  @foreach ($options as $o)
    <a href="{{ $o['goto'] . "?message={$currentMessage}&answer=" . urlencode($o['display']) }}"
      class="cursor-pointer w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-pink-50 text-gray-800 shadow-sm hover:bg-pink-100 hover:text-gray-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:hover:text-neutral-300 ">
      {{ $o['display'] }}
    </a>
  @endforeach

  <script>
    document.addEventListener('DOMContentLoaded', function() {
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



