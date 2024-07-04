<x-layouts.public title="コスメ★ピシャット">

  <div class="flex justify-center items-center mt-8">
    {{-- 女性のイラスト --}}
    <img src="/img/josei2.png" alt="Home Icon" class="h-32 w-32">
  </div>

  <h1 class="text-lg mt-4 mb-8 mx-4" id="animated-message"></h1>

  <form action="{{ url($goto) }}" method="get" id="form">
    <ul class="flex flex-col">
      @foreach ($options as $o)
        <li
          class="inline-flex items-center gap-x-2.5 pl-4 text-sm font-medium rounded-lg border border-gray-200 bg-pink-50 text-gray-800 shadow-sm hover:text-gray-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:hover:text-neutral-300"
        >
          <div class="relative flex items-start w-full">
            <div class="flex items-center py-3">
              <input type="checkbox" name="checked[]" id="chb-{{ $loop->index }}" value="{{ $o['display'] }}"
                class="checkboxes shrink-0 border-gray-200 rounded disabled:opacity-50 disabled:pointer-events-none ">
            </div>
            <label for="chb-{{ $loop->index }}"
              class="block w-full pl-2 py-3 text-sm text-gray-600 dark:text-neutral-500">
              {{ $o['display'] }}
            </label>
          </div>
        </li>
      @endforeach
    </ul>
    <div class="w-full flex justify-center items-center pt-2">
      <input type="submit"
        class="py-1 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-pink-200 text-gray-800 hover:bg-pink-300 disabled:opacity-50 disabled:pointer-events-none"
        value="OK" />
    </div>
  </form>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('form').addEventListener('submit', (event) => {
        const checked = document.querySelectorAll('input[type="checkbox"]:checked');
        if (!checked || checked.length === 0) {
          event.preventDefault();
          alert('選択してください');
        }else{
          // チェックボックスのvalueを配列に変換する
          const checkedValues = Array.from(checked).map((el) => el.value);

          // 選択内容をローカルストレージに保存する
          localStorage.setItem('{{ $currentMessage }}', JSON.stringify(checkedValues));
        }
      });

      // checkboxesクラスを持つ要素を取得する
      const checkboxes = document.querySelectorAll('.checkboxes');
      // localStorageに保存された選択内容を取得する
      const checkedValues = JSON.parse(localStorage.getItem('{{ $currentMessage }}')) || [];
      // ローカルストレージに保存されていた、チェックされている項目に合致する場合、チェックする
      checkboxes.forEach((el) => {
        if (checkedValues.includes(el.value)) {
          el.checked = true;
        }
      });

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
