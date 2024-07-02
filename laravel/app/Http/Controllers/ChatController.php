<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\App;
use App\Models\TChat;
use App\Models\TProduct;
use App\Models\TValiation;
use App\Services\OsusumeParams;
use Exception;

use App\Services\RgbColor;

class ChatController extends Controller
{
    /**
     * 回答を保持するセッションキー
     */
    const SESSION_KEY_ANSWERS = 'answers';

    /**
     * 会話分岐のメッセージ
     *
     * @var array
     */
    private static $messages = [
        'index' => [
            'message' => "こんにちは。コスメピシャットへようこそ！\n本日はどのような目的でご来店いただいたのですか？",
            'options' => [
                ['display' => '新しいアイテムが欲しい。', 'goto' => '/chat/ask-purpose'],
                ['display' => '良さげなものがあれば買いたい。', 'goto' => '/chat/best-one-lead'],
                ['display' => 'どんな商品があるか情報だけを知りたい。', 'goto' => '/chat/best-one-lead']
            ],
            'multiple' => false
        ],
        'best-one-lead' => [
            'message' => "当店では、約◯種類のリップアイテムを取り扱っております。これからあなたにピッタリ似合うリップを探すお手伝いをさせてください！
                それでは、あなたにふさわしい運命の１本を見つけるために、いくつか質問をしていきます。深く考えず、直感で答えてくださいね。",
            'options' => [
                ['display' => '次へ', 'goto' => '/chat/price-select'],
            ],
            'multiple' => false
        ],
        'ask-purpose' => [
            'message' => "今回の購入目的を教えていただけますか？",
            'options' => [
                ['display' => '良さげなものだったら新しいアイテムが欲しい。', 'goto' => '/chat/best-one-lead'],
                ['display' => '今使用しているものがなくなりそう。', 'goto' => '/chat/repeat'],
                ['display' => '今使用しているものに不満、またはお悩みやトラブルがある。', 'goto' => '/chat/now-use'],
                ['display' => '印象を変えたい。', 'goto' => '/chat/impression'],
            ],
            'multiple' => false
        ],
        'repeat' => [
            'message' => "リピート購入しますか？",
            'options' => [
                ['display' => 'はい。', 'goto' => '/chat/repeat-buy'], //ここまだ
            ],
            'multiple' => false
        ],
        'repeat-buy' => [
            'message' => "リピート購入しますか？",
            'options' => [
                ['display' => 'メーカー、商品名色番号を教えてください。', 'goto' => '/chat/'], //ここのページ打ち込み型にする？
            ],
            'multiple' => false
        ],
        'now-use' => [
            'message' => "今使っているもののメーカー、商品名、色番号を教えてください。",
            'options' => [
                ['display' => 'メーカー、商品名色番号を教えてください。', 'goto' => '/chat/nayami'], //ここのページ打ち込み型にする？
            ],
            'multiple' => false
        ],
        'nayami' => [
            'message' => "具体的にはどんな不満やお悩みがありますか？",
            'options' => [
                ['display' => '色持ちが悪い', 'goto' => '/osusume'], //ここまだ
                ['display' => '乾燥する', 'goto' => '/osusume'], //ここまだ
                ['display' => '唇の色ムラ', 'goto' => '/osusume'], //ここまだ
                ['display' => '縦じわ', 'goto' => '/osusume'], //ここまだ
                ['display' => '合わない成分がある', 'goto' => '/chat/seibun'],
            ],
            'multiple' => false
        ],
        'seibun' => [
            'message' => "合わない成分を選択して下さい（複数選択可）？",
            'options' => [
                ['display' => 'ミツロウ、パラペン、など', 'goto' => '/chat/'], //ここのページ打ち込み型にする？

            ],
            'multiple' => false
        ],
        // ここから大枠のヒアリング　ーーーーーーーーーーーーーーーーーーーーーーーーー
        'price-select' => [
            'message' => "１本あたりの予算はどれくらいでしょうか？",
            'options' => [
                ['display' => '1,000円未満', 'goto' => '/chat/scene-select'],
                ['display' => '1,000円〜2,000円未満', 'goto' => '/chat/scene-select'],
                ['display' => '2,000円〜3,000円未満', 'goto' => '/chat/scene-select'],
                ['display' => '3,000円以上', 'goto' => '/chat/scene-select'],
            ],
            'multiple' => false
        ],
        'scene-select' => [
            'message' => "今回お探しのアイテムは、普段使いですか？それとも特別な日のためにお探しですか？",
            'options' => [
                ['display' => '普段使い', 'goto' => '/chat/impression'],
                ['display' => '特別な日', 'goto' => '/chat/scene'],
            ],
            'multiple' => false
        ],
        'impression' => [
            'message' => "どんな印象に見られたいですか？",
            'options' => [
                ['display' => 'ゴージャス、華やか', 'goto' => '/osusume/impression'],
                ['display' => '女性らしい、スイート', 'goto' => '/osusume/impression'],
                ['display' => 'クール、知的', 'goto' => '/osusume/impression'],
                ['display' => 'ヘルシー、はつらつ', 'goto' => '/osusume/impression'],
            ],
            'multiple' => false
        ],
        'scene' => [
            'message' => "どんなシーンで使う予定ですか？",
            'options' => [
                ['display' => '結婚式・パーティー', 'goto' => '/osusume/scene'],
                ['display' => 'デート・婚活', 'goto' => '/osusume/scene'],
                ['display' => '就職活動', 'goto' => '/osusume/scene'],
                ['display' => 'アクティブな日', 'goto' => '/osusume/scene'],
            ],
            'multiple' => false
        ],
        // ーーーーーーーーーーーー　ここから色　ーーーーーーーーーーーー
        'red' => [
            'message' => "華やかなレッド系がおすすめです。",
            'options' => [
                ['display' => '次へ', 'goto' => '/chat/shitukan'], //ここまだ
            ],
            'multiple' => false
        ],
        'pink' => [
            'message' => "キュートで可愛らしいピンク系がおすすめです。",
            'options' => [
                ['display' => '次へ', 'goto' => '/chat/shitukan'], //ここまだ
            ],
            'multiple' => false
        ],
        'beige' => [
            'message' => "知的でナチュラルなベージュ系がおすすめです。",
            'options' => [
                ['display' => '次へ', 'goto' => '/chat/shitukan'], //ここまだ
            ],
            'multiple' => false
        ],
        'orange' => [
            'message' => "アクティブではつらつとしたオレンジ系がおすすめです。",
            'options' => [
                ['display' => '次へ', 'goto' => '/chat/shitukan'], //ここまだ
            ],
            'multiple' => false
        ],
        // ーーーーーーーーーーーー　ここから詳細ヒアリング　ーーーーーーーーーーーー
        'shitukan' => [
            'message' => "どんな質感がお好みですか？", //ここの質問ローカルホストに保存？
            'options' => [
                ['display' => 'ツヤ', 'goto' => '/chat/color-select'],
                ['display' => 'マット', 'goto' => '/chat/color-select'],
                ['display' => 'シアー', 'goto' => '/chat/color-select'],
                ['display' => 'メタリック', 'goto' => '/chat/color-select'],
            ],
            'multiple' => false
        ],
        'color-select' => [
            'message' => "どんな色がお好みですか？（1つ選ぶ）", //ここの質問ローカルホストに保存？
            'options' => [
                ['display' => 'レッド系', 'goto' => '/chat/brand-select'],
                ['display' => 'ピンク系', 'goto' => '/chat/brand-select'],
                ['display' => 'ブラウン系', 'goto' => '/chat/brand-select'],
                ['display' => 'ローズ・ワイン系', 'goto' => '/chat/brand-select'],
                ['display' => 'ベージュ系', 'goto' => '/chat/brand-select'],
                ['display' => 'オレンジ系', 'goto' => '/chat/brand-select'],
            ],
            'multiple' => false //複数選択可にする
        ],
        'brand-select' => [
            'message' => "好きなブランドはありますか？（複数選択可）", //ここの質問ローカルホストに保存？
            'options' => [
                ['display' => 'CANMAKE', 'goto' => '/chat/maker-select'],
                ['display' => 'MAYBALLINE', 'goto' => '/chat/maker-select'],
                ['display' => 'ちふれ', 'goto' => '/chat/maker-select'],
                ['display' => 'ルナソル', 'goto' => '/chat/maker-select'],
                ['display' => 'KATE', 'goto' => '/chat/maker-select'],
                ['display' => 'RMK', 'goto' => '/chat/maker-select'],
            ],
            'multiple' => false //複数選択可にする
        ],
        'maker-select' => [
            'message' => "好きなメーカーはありますか？（複数選択可）", //ここの質問ローカルホストに保存？
            'options' => [
                ['display' => '資生堂', 'goto' => '/chat/personal-color'],
                ['display' => 'Kanebo.', 'goto' => '/chat/personal-color'],
                ['display' => 'KOSE.', 'goto' => '/chat/personal-color'],
                ['display' => 'Dior', 'goto' => '/chat/personal-color'],
                ['display' => 'CHANEL', 'goto' => '/chat/personal-color'],
            ],
            'multiple' => false //複数選択可にする
        ],
        'personal-color' => [
            'message' => "パーソナルカラー診断をしたことがありますか？", //ここの質問ローカルホストに保存？
            'options' => [
                ['display' => 'ある', 'goto' => '/chat/user-personal-color'],
                ['display' => 'したことないので診断する', 'goto' => '/chat/color-check'], //ここまだ（パーソナルカラー診断）
                ['display' => '今回は診断せずに進めたい', 'goto' => '/osusume'],
            ],
            'multiple' => false
        ],
        'user-personal-color' => [
            'message' => "あなたのパーソナルカラーを選択して下さい？", //ここの質問ローカルホストに保存？
            'options' => [
                ['display' => 'イエベ春', 'goto' => '/osusume'], //ここまだ
                ['display' => 'ブルベ夏', 'goto' => '/osusume'],
                ['display' => 'イエベ秋', 'goto' => '/osusume'],
                ['display' => 'ブルベ冬', 'goto' => '/osusume'], //おすすめ３本からチャットGPTのページに飛ばす方法　まだ
            ],
            'multiple' => false
        ],

        // ここからリコメンド　ーーーーーーーーーーーーーーーーーーーーーーーー ChatGPTのページに飛ばす？？

        // ーーーーーーーーーーーーーーーーーーーーーーーーここまで？

        // ここからクロージングーーーーーーーーーーーーーーーーーーーーーーーー
        'closing' => [
            'message' => "◯◯という理由で、こちらの３本がお似合いだと思います！
                            お客様はこちらの商品をについてどう思われますか？",
            'options' => [
                ['display' => 'とても気に入った', 'goto' => '/buy'],
                ['display' => '気に入った', 'goto' => '/buy'],
                ['display' => 'あまり好みではない', 'goto' => '/again'],
                ['display' => '全く好みではない', 'goto' => '/again'],
            ],
            'multiple' => false
        ],
        'buy' => [
            'message' => "これで決定しますか？",
            'options' => [
                ['display' => 'はい', 'goto' => '/thank-you'],
            ],
            'multiple' => false
        ],
        'thank-you' => [
            'message' => "ありがとうございます！お客様のサポートができて心より嬉しく思います。新しいリップを着けて、気分を上げてくださいね！",
            'options' => [
                ['display' => 'はい', 'goto' => '/thank-you'], //ここまだ
            ],
            'multiple' => false
        ],
        'again' => [
            'message' => "もう一度選び直しますか？",
            'options' => [
                ['display' => '再トライ', 'goto' => '/thank-you'], //どこに戻る？
                ['display' => '今回はやめておく', 'goto' => '/'], //どこに飛ばす？
            ],
            'multiple' => false
        ],
    ];

    public function index(Request $request)
    {
        // トップ画面に来たら、セッション内の回答を削除
        $request->session()->forget(self::SESSION_KEY_ANSWERS);

        return $this->chat($request, 'index');
    }

    /**
     * おすすめ商品の表示
     *
     * @param Request $request リクエスト
     * @param 'impression'|'scene' $from 遷移元
     * @return void
     */
    public function osusume(Request $request, $from)
    {
        $params = new OsusumeParams();

        // ここで、$fromによって、おすすめの商品を取得する処理を書く
        switch ($from) {
            case 'impression':
                // 印象によっておすすめの商品を取得
                switch ($request->answer) {
                    case 'ゴージャス、華やか':
                        // レッド系、ローズワイン系
                        $params->colors = [new RgbColor('#D7514D'), new RgbColor('#B0737B')];
                        break;
                    case '女性らしい、スイート':
                        // ピンク系
                        $params->colors = [new RgbColor('#DD609A')];
                        break;
                    case 'クール、知的':
                        // ベージュ系、ブラウン系
                        $params->colors = [new RgbColor('#CF9D5E'), new RgbColor('#A06757')];
                        break;
                    case 'ヘルシー、はつらつ':
                        // オレンジ系
                        $params->colors = [new RgbColor('#EBAB54')];
                        break;
                    default:
                        throw new Exception('不正な選択です。');
                }
                break;
            case 'scene':
                switch ($request->answer) {
                    case '結婚式・パーティー':
                        // レッド系、ローズワイン系
                        $params->colors = [new RgbColor('#D7514D'), new RgbColor('#B0737B')];
                        break;
                    case 'デート・婚活':
                        // ピンク系
                        $params->colors = [new RgbColor('#DD609A')];
                        break;
                    case '就職活動':
                        // ベージュ系、ブラウン系
                        $params->colors = [new RgbColor('#CF9D5E'), new RgbColor('#A06757')];
                        break;
                    case 'アクティブな日':
                        // オレンジ系
                        $params->colors = [new RgbColor('#EBAB54')];
                        break;
                    default:
                        throw new Exception('不正な選択です。');
                }
                // シーンによっておすすめの商品を取得
                break;
        }

        $recommendations = TProduct::getRecommendations($params);

        return view('osusume', compact('recommendations'));
    }


    // Routeに以下の指定をしておくと、
    // Route::get('/chat/{message}', [ChatController::class, 'chat']);
    //
    // localhost/chat/ask-purpose   => $message 'ask-purpose'
    // localhost/chat/best-one-lead => $message 'best-one-lead'
    // 様になって、このメソッドが呼び出される。
    public function chat(Request $request, string $message)
    {
        $thisMessage = self::$messages[$message];
        $currentMessage = $message;
        $message = $thisMessage['message'];
        $options = $thisMessage['options'];
        $multiple = $thisMessage['multiple'];

        // デバッグ環境では、セッションに格納した情報を表示させる。
        // if (env('APP_ENV') === 'local') {
        //     var_dump($request->session()->get(self::SESSION_KEY_ANSWERS));
        // }

        return view('singleanswer', compact('message', 'options', 'multiple', 'currentMessage'));
    }

// ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーここ付け足したとこ（あー）
    // フォーム表示のメソッドを追加
    public function form()
    {
        $message = "入力フォームにメッセージを表示します";
        $options = [
            ['display' => 'オプション1', 'goto' => '/next1'],
            ['display' => 'オプション2', 'goto' => '/next2'],
        ];
        $multiple = false;

        return view('form', compact('message', 'options', 'multiple'));
    }

public function multiple(Request $request)
{
    $selectedOptions = $request->input('options', []);
    
    // 選択されたオプションを処理するロジックをここに追加
    // 例えば、ログを表示したり、データベースに保存したりする

        return view('result', compact('selectedOptions'));
    }
    // ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーここまで


    public function passer(Request $request)
    {
        $data = [];

        function safeEscape($str)
        {
            $str = trim($str);
            $str = str_replace("'", " ", $str);
            $str = str_replace('"', " ", $str);
            $str = str_replace("\r", " ", $str);
            $str = str_replace("\n", " ", $str);

            return $str;
        }

        $trimmedPostedMessage = safeEscape($request->message);
        $trimmedPostedThreadId = safeEscape($request->thread_id);

        $data['message'] = $trimmedPostedMessage;
        $data['thread_id'] = $trimmedPostedThreadId;

        function addLogToDb($thread_id, $role, $message)
        {
            $chat = new TChat();
            $chat->thread_id = $thread_id;
            $chat->role = $role;
            $chat->message = $message;
            $chat->save();
        }

        // スレッドIDが登録されている場合は、ログを登録する。
        // スレッドIDが未発行の場合は、返答取得時に登録する。
        if ($trimmedPostedThreadId) {
            addLogToDb($trimmedPostedThreadId, 'user', $trimmedPostedMessage);
        }

        try {
            $order = "node " . base_path() . "/util/openai.mjs \"$trimmedPostedMessage\" \"$trimmedPostedThreadId\"";
            $openAiRet = exec($order, $output);

            $ret = json_decode($openAiRet, true);
            $thread_id = $ret['thread_id'];

            // ユーザーメッセージ未登録の場合は登録する。
            if (!$trimmedPostedThreadId) {
                addLogToDb($thread_id, 'user', $trimmedPostedMessage);
            }

            // ボットメッセージを登録する。
            addLogToDb($thread_id, 'bot', $openAiRet);

            return $ret;
        } catch (Exception $e) {
            $errMsg = 'Error: ' . $e->getMessage();
            $json = ['message' => $errMsg];

            // ユーザーメッセージ未登録の場合は登録する。
            if (!$trimmedPostedThreadId) {
                // スレッドIDは生成されていないので、独自に生成し、ユーザーとボットのエラーの対応関係が取れる様にしておく。
                $trimmedPostedThreadId = uniqid('passer_err_');
                addLogToDb($trimmedPostedThreadId, 'user', $trimmedPostedMessage);
            }

            // ボットメッセージはエラーログを登録する。
            addLogToDb($trimmedPostedThreadId, 'bot', $errMsg);
        }
    }
}
