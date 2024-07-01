<x-layouts.public title="シングルアンサー | コスメ★ピシャット">
    <body>
      <h1 class="text-3xl">{{ nl2br(e($message)) }}</h1>
      <form action="/multiple" method="POST">
        @csrf
        @foreach ($options as $o)
          <div class="flex items-center mb-4">
            <input id="option-{{ $loop->index }}" type="checkbox" name="options[]" value="{{ $o['goto'] }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
            <label for="option-{{ $loop->index }}" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
              {{ $o['display'] }}
            </label>
          </div>
        @endforeach
        <button type="submit" class="mt-4 py-2 px-4 bg-blue-500 text-white rounded">送信</button>
      </form>
    </body>
</x-layouts.public>
