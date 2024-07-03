{{-- $href=遷移先 / $display=表示内容 --}}
<a
  href="{!! $href !!}"
  id="{!! isset($id) ? $id : '' !!}"
  @if (isset($target)) target="{{ $target }}" @endif
  class="cursor-pointer w-full py-{{ isset($py) ? $py : '3' }} px-{{ isset($px) ? $px : '4' }} inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-pink-50 text-gray-800 shadow-sm hover:bg-pink-100 hover:text-gray-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:hover:text-neutral-300"
>
  {{ $display }}
</a>
