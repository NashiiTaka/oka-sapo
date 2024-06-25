// for development
// import $ from 'jQuery';

/**
 * 文字入力タイピングのインターバル
 * @type {number}
 */
const TYPE_INTERVAL_MS = 30;

/**
 * ボタン表示のインターバル
 * @type {number}
 */
const BUTTON_DISP_INTERVAL_MS = 120;

/**
 * 初期表示内容
 * @type {string}
 */
const initialTalk = {
  message: "こんにちは！どの種類の家電製品についてお手伝いさせていただけますか？",
  options: ["その他", "キッチン家電", "洗濯家電", "家庭エンターテイメント", "冷暖房家電", "小型家電", "パーソナルケア", "コンピュータ/タブレット/スマートフォン", "ガジェット"],
  multiple: false,
  etcCustomeDisp: "入力して指定",
}

/**
 * 現在のチャットの番号。ボット × Youのセットで1つの番号としている。
 * @type {number}
 */
let chatNo = 0;

/**
 * ChatのスレッドID、URLにも反映させる。
 * @type {string}
 */
let thread_id = '';

$(document).ready(function () {
  // TODO: URLにスレッド番号があり、変数が初期状態の場合はリロード等。要復元

  /**
   * 文字を入力する。
   * @param {string} text 
   * @param {HTMLElement} element 
   */
  function typeText(text, element) {
    // 中身を空にする。
    element.html('');

    return new Promise(resolve => {
      let index = 0;
      function type() {
        if (index < text.length) {
          element.html(text.substring(0, index + 1) + (index < text.length - 1 ? '●' : ''));
          index++;
          if (index < text.length) {
            setTimeout(type, TYPE_INTERVAL_MS); // Speed of typing
          } else {
            resolve();
          }
        }
      }
      type();
    })
  }

  /**
   * チャットを進める。
   * @param {string} message ボットに伝える、ユーザーの入力内容
   */
  function proceedChat(messageOrResponseJson) {
    chatNo++;

    // 現在のチャットを表示する。
    $(`#chat-${chatNo}`).removeClass('hidden');

    if (typeof (messageOrResponseJson) === 'object') {
      appendChat(messageOrResponseJson);
    } else {
      const formData = new FormData();
      formData.append('message', messageOrResponseJson);
      formData.append('thread_id', thread_id);
      console.log('post message:' + messageOrResponseJson);
      axios.post('./passer', formData, { timeout: 10000 })
        .then(function (response) {
          const json = response.data?.value ? response.data?.value : response.data;

          console.log(json);

          if (!thread_id) {
            thread_id = json.thread_id;
            history.replaceState(null, null, '?tid=' + encodeURIComponent(thread_id));
          }

          appendChat(json);
        })
        .catch(function (error) {
          console.error(error);
        });
    }
  }

  /**
   * チャットに追記する。
   * @param {*} json 
   */
  function appendChat(json) {
    // まずは、雛形を複製しておき、次のチャットに備えておく。
    const container = $('#chat-area');
    const template = $('#chat-template').html().replace(/chatno/g, chatNo + 1);
    container.append(template);

    // 現在のチャットにメッセージの出力を開始する
    typeText(json.message, $(`#bot-${chatNo}`)).then(() => {
      const etcCustomeDisp = json.etcCustomeDisp;

      // ボットテキスト出力完了後
      $(`#you-wrap-${chatNo}`).removeClass('hidden');

      // 選択肢がある場合の処理
      if (json.options && json.options.length > 0) {
        const tgt = $(`#you-${chatNo}`);

        if (!json.options.some(o => o.match(/その他/))) {
          json.options.push('その他');
        }

        if (json.multiple) {
          const ul = $(`<ul class="flex flex-col" id="ul-${chatNo}"></ul>`);
          tgt.append(ul);

          const m = json.options.map((o, i) => {
            return `
              <li class="inline-flex items-center gap-x-2.5 pl-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                <div class="relative flex items-start w-full">
                  <div class="flex items-center py-3">
                    <input
                      type="checkbox"
                      id="chb-${chatNo}-${i}"
                      name="chb-${chatNo}[]"
                      value="${o}"
                      class="shrink-0 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                    >
                  </div>
                  <label
                    for="chb-${chatNo}-${i}"
                    class="block w-full pl-2 py-3 text-sm text-gray-600 dark:text-neutral-500"
                  >
                    ${o}
                  </label>
                </div>
              </li>`;
          });

          const idOkBtn = `btn-chb-ok-${chatNo}`;
          appendChb(m, ul).then(() => {
            tgt.append(
              `<div class="w-full flex justify-center items-center pt-2">` +
              `  <input type="submit" id="${idOkBtn}" class="py-1 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" value="OK" />` +
              `</div>`
            );
            $(`#${idOkBtn}`).on('click', (e) => {
              e.preventDefault();

              // チェックされているチェックボックスの値を収集するためのリスト
              var checkedValues = [];
              // すべてのチェックされているチェックボックス要素を取得
              $(`#ul-${chatNo} input[type="checkbox"]:checked`).each(function () {
                checkedValues.push($(this).val());
              });

              if (checkedValues.length) {
                // OKボタンを削除する。
                $(`#${idOkBtn}`).remove();
                // すべてのチェックボックスを読み取り専用にする
                $(`#ul-${chatNo} input[type="checkbox"]`).on('click', function (event) {
                  event.preventDefault();
                });
                proceedChat(checkedValues.join(','));
              } else {
                alert('選択して下さい。');
              }
            });
          });
        }
        else {
          const m = json.options.map((o) => {
            return `<div class="btns-${chatNo}${o.match(/その他/) ? ' btn-etc' : ''} cursor-pointer w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
  ${o.match(/その他/) ? etcCustomeDisp || 'なんでも聞いて下さい' : o}
</div>`
          });

          appendButton(m, tgt);
        }
      }
      // 選択肢がない場合 = フリーテキスト入力
      else {
        const idOkIpt = `btn-free-input-${chatNo}`;
        const idOkBtn = `btn-free-ok-${chatNo}`;
        $(`#you-${chatNo}`).html(freeTextInput(idOkIpt, idOkBtn));
        $(`#${idOkBtn}`).on('click', onFreeTextInputCompleted);
        $(`${idOkIpt}`).focus();
      }

    });
  }

  function appendChb(chbs, tgt) {
    return new Promise(resolve => {
      let currentChbIndex = 0;
      function appenEachChb() {
        if (currentChbIndex < chbs.length) {
          setTimeout(() => {
            tgt.append(chbs[currentChbIndex++]);
            if (currentChbIndex < chbs.length) {
              appenEachChb();
            } else {
              resolve();
            }
          }, BUTTON_DISP_INTERVAL_MS);
        } else {
          resolve();
        }
      }
      appenEachChb();
    })
  }

  /**
   * ユーザーが選択するボタンの追加
   * @param {[]} btns ボタン要素の配列
   * @param {HTMLElement} 追加先のコンテナ要素
   */
  function appendButton(btns, tgt) {
    let currentBtnIndex = 0;
    function appenEachButton() {
      if (currentBtnIndex < btns.length) {
        setTimeout(() => {
          tgt.append(btns[currentBtnIndex++]);
          if (currentBtnIndex < btns.length) {
            appenEachButton();
          } else {
            $(`.btns-${chatNo}`).on('click', onSingleBtnClicked);
          }
        }, BUTTON_DISP_INTERVAL_MS);
      }
    }
    appenEachButton();
  }

  /**
   * 選択肢のボタンが押下された時のイベントハンドラ
   * @param {*} e イベント情報
   */
  function onSingleBtnClicked(e) {
    const tgtJElem = $(e.target);

    const idOkIpt = `btn-etc-input-${chatNo}`;
    const idOkBtn = `btn-etc-ok-${chatNo}`;

    // その他ボタンのときは、入力欄とOKボタンを表示する。
    if (tgtJElem.hasClass('btn-etc')) {
      tgtJElem.off('click');
      tgtJElem.html(freeTextInput(idOkIpt, idOkBtn));
      $(`#${idOkIpt}`).focus();

      // OKボタンのクリックイベントの設定
      $(`#${idOkBtn}`).on('click', (evOkBtn) => {
        evOkBtn.preventDefault();
        const inputed = $(`#${idOkIpt}`).val();
        tgtJElem.html(inputed || 'なんでも聞いて下さい');

        if (inputed) {
          e.target.className = 'w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none';
          $(`.btns-${chatNo}`).off('click');
          proceedChat(inputed);
        } else {
          // setTimeoutを使わないと、今実行中のイベントもハンドルしてしまった。
          setTimeout(() => tgtJElem.on('click', onSingleBtnClicked), 0);
        }
      });
    }
    // その他ボタン以外のときは、処理を進める。
    else {
      // 複数選択を避けるため、イベントハンドリングを停止
      $(`.btns-${chatNo}`).off('click');

      // OKボタンが表示されている場合は削除、インプットも入力できないようにする。
      $(`#${idOkIpt}`).attr('disabled', true).addClass('bg-gray-300 cursor-not-allowed');
      $(`#${idOkBtn}`).remove();

      e.target.className = 'w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none';
      proceedChat($(e.target).html().trim());
    }
  }

  function onFreeTextInputCompleted(e) {
    e.preventDefault();
    const okBtn = $(e.target);
    const freeIpt = okBtn.siblings('input')[0];
    const inputed = freeIpt.value.trim();
    if (!inputed) {
      alert('入力して下さい');
    } else {
      const container = okBtn.parentsUntil('div');
      container.html(
        `<div class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
          ${inputed}
        </div>`
      );

      proceedChat(inputed);
    }
  }

  function freeTextInput(idOkIpt, idOkBtn) {
    return `<form class="flex w-full">` +
      `<input type="text" id="${idOkIpt}" class="peer py-1 pe-0 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-500 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none dark:border-b-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 dark:focus:border-b-neutral-600" placeholder="入力して下さい">` +
      `<input type="submit" id="${idOkBtn}" class="py-1 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" value="OK" />` +
      `</form>`;
  }

  // 初期化が終わったら、初期メッセージを表示
  proceedChat(initialTalk);
});
