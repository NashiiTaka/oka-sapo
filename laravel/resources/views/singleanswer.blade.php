<x-layouts.public title="シングルアンサー | コスメ★ピシャット">

  <body>
    <h1 class="text-3xl">{{ nl2br(e($message)) }}</h1>
    @foreach ($options as $o)
      <a href="{{ $o['goto'] . "?message={$currentMessage}&answer=" . urlencode($o['display']) }}"
        class="cursor-pointer w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
        {{ $o['display'] }}
      </a>
    @endforeach
  </body>
</x-layouts.public>
