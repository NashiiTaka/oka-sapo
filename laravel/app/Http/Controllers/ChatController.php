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
    public function firstView()
    {
        return view('first-view');
    }
    


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
            'message' => "こんにちは。コスメピシャットへようこそ！本日は何かお手伝いできることはありますか？",
            'options' => [
                ['display' => '新しいアイテムが欲しい。', 'goto' => '/chat/ask-purpose'],
                ['display' => '良さそうなものがあれば買いたい。', 'goto' => '/chat/best-one-lead'],
                ['display' => 'どんな商品があるか情報を知りたい。', 'goto' => '/chat/best-one-lead']
            ],
            'multiple' => false
        ],
        'best-one-lead' => [
            'message' => "当店では、300種類以上のリップアイテムを取り扱っております。これからあなたにピッタリ似合うリップを探すお手伝いをさせてください！",
            'options' => [
                ['display' => '次へ', 'goto' => '/chat/best-one-lead2'],
            ],
            'multiple' => false
        ],
        'best-one-lead2' => [
            'message' => "それでは、あなたにふさわしい運命の１本を見つけるために、いくつか質問をしていきます。深く考えず、直感で答えてくださいね。",
            'options' => [
                ['display' => '次へ', 'goto' => '/chat/price-select'],
            ],
            'multiple' => false
        ],
        'ask-purpose' => [
            'message' => "今回の購入目的を教えていただけますか？",
            'options' => [
                ['display' => '良さそうなものだったら新調したい。', 'goto' => '/chat/best-one-lead'],
                ['display' => '今使用しているものがなくなりそう。', 'goto' => '/chat/repeat'],
                ['display' => '今使用しているものに不満、またはお悩みやトラブルがある。', 'goto' => '/chat/now-use'],
                ['display' => '印象を変えたい。', 'goto' => '/chat/impression'],
            ],
            'multiple' => false
        ],
        'repeat' => [
            'message' => "リピート購入しますか？",
            'options' => [
                ['display' => 'はい。', 'goto' => '/chat/repeat-buy'],
            ],
            'multiple' => false
        ],
        'repeat-buy' => [
            'message' => "リピート購入しますか？",
            'options' => [
                ['display' => 'メーカー、商品名、色番号を教えてください。', 'goto' => '/chat/'], //ここのページ打ち込み型にする？
            ],
            'multiple' => false
        ],
        'now-use' => [
            'message' => "今使っているもののメーカー、商品名、色番号を教えてください。",
            'options' => [
                ['display' => 'メーカー、商品名、色番号を教えてください。', 'goto' => '/chat/nayami'], //ここのページ打ち込み型にする？
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
            'message' => "１本あたりの予算を教えてください。",
            'options' => [
                ['display' => '1,000円未満', 'goto' => '/chat/scene-select'],
                ['display' => '1,000円〜2,000円', 'goto' => '/chat/scene-select'],
                ['display' => '2,000円〜3,000円', 'goto' => '/chat/scene-select'],
                ['display' => '3,000円以上', 'goto' => '/chat/scene-select'],
            ],
            'multiple' => false
        ],
        'scene-select' => [
            'message' => "今回お探しのアイテムは、普段使いですか？それとも、特別な日のためにお探しですか？",
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
                ['display' => '就職活動・証明写真', 'goto' => '/osusume/scene'],
                ['display' => 'アクティブな日', 'goto' => '/osusume/scene'],
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
            'message' => "どんな色がお好みですか？",
            'options' => [
                ['display' => 'レッド系', 'bg-color' => '#D7514D'],
                ['display' => 'ピンク系', 'bg-color' => '#DD609A'],
                ['display' => 'ブラウン系', 'bg-color' => '#A06757'],
                ['display' => 'ローズ・ワイン系', 'bg-color' => '#B0737B'],
                ['display' => 'ベージュ系', 'bg-color' => '#CF9D5E'],
                ['display' => 'オレンジ系', 'bg-color' => '#EBAB54'],
            ],
            'goto' => '/chat/brand-select',
            'multiple' => true //複数選択可にする
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
            'message' => "あなたのパーソナルカラーを選択して下さい。", //ここの質問ローカルホストに保存？
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

        // ここからパーソナルカラー診断ーーーーーーーーーーーーーーーーーー
        'color-check' => [
            'message' => "肌はどちらかというと明るめですか？",
            'options' => [
                ['display' => 'はい', 'goto' => '/chat/color-check-1'],
                ['display' => 'いいえ', 'goto' => '/chat/color-check-2'],
            ],
            'multiple' => false
        ],
        'color-check-1' => [
            'message' => "地毛の色が暗めの茶色、もしくは黒に近いですか？",
            'options' => [
                ['display' => 'はい', 'goto' => '/chat/color-check-1-1'],
                ['display' => 'いいえ', 'goto' => '/chat/color-check-1-2'],
            ],
            'multiple' => false
        ],
        'color-check-1-1' => [
            'message' => "肌色が良くないと言われることが多いですか？",
            'options' => [
                ['display' => 'はい', 'goto' => '/chat/color-check-1-1-1'],
                ['display' => 'いいえ', 'goto' => '/chat/color-check-1-2'],
            ],
            'multiple' => false
        ],
        'color-check-1-1-1' => [
            'message' => "洋服などは、はっきりとしたコントラストのある色がなじみますか？",
            'options' => [
                ['display' => 'はい', 'goto' => '/chat/burube-fuyu'],
                ['display' => 'いいえ', 'goto' => '/chat/iebe-aki'],
            ],
            'multiple' => false
        ],
        // ーーーーーーーーーーーーーー
        'color-check-1-2' => [
            'message' => "日焼けしやすいですか？",
            'options' => [
                ['display' => 'はい', 'goto' => '/chat/color-check-1-2-1'],
                ['display' => 'いいえ', 'goto' => '/chat/color-check-1-2-2'],
            ],
            'multiple' => false
        ],
        'color-check-1-2-1' => [
            'message' => "洋服などはブラウン、ゴールド、やスモーキーカラーがなじみますか？",
            'options' => [
                ['display' => 'はい', 'goto' => '/chat/iebe-aki'],
                ['display' => 'いいえ', 'goto' => '/chat/burube-fuyu'],
            ],
            'multiple' => false
        ],
        'color-check-1-2-2' => [
            'message' => "洋服などは黒、ネイビー、グレーなどの無彩色がしっくりきますか？",
            'options' => [
                ['display' => 'はい', 'goto' => '/chat/burube-natu'],
                ['display' => 'いいえ', 'goto' => '/chat/iebe-haru'],
            ],
            'multiple' => false
        ],

        // ーーーーーーーーーーーーーーー
        'color-check-2' => [
            'message' => "地毛の色が暗めの茶色、もしくは黒に近いですか？",
            'options' => [
                ['display' => 'はい', 'goto' => '/chat/color-check-1-2'],
                ['display' => 'いいえ', 'goto' => '/chat/color-check-2-2'],
            ],
            'multiple' => false
        ],
        'color-check-2-2' => [
            'message' => "頬に赤みが出やすいですか？",
            'options' => [
                ['display' => 'はい', 'goto' => '/chat/color-check-1-2-2'],
                ['display' => 'いいえ', 'goto' => '/chat/color-check-2-2-2'],
            ],
            'multiple' => false
        ],
        'color-check-2-2-2' => [
            'message' => "洋服などは黒、ネイビー、グレーなどの無彩色がしっくりきますか？",
            'options' => [
                ['display' => 'はい', 'goto' => '/chat/burube-natu'],
                ['display' => 'いいえ', 'goto' => '/chat/iebe-haru'],
            ],
            'multiple' => false
        ],
        'iebe-aki' => [
            'message' => "あなたのパーソナルカラーはイエベ秋です。",
            'options' => [
                ['display' => '診断を終了し、HOMEに戻る', 'goto' => '/chat/index'],
            ],
            'multiple' => false
        ],
        'burube-fuyu' => [
            'message' => "あなたのパーソナルカラーはブルベ冬です。",
            'options' => [
                ['display' => '診断を終了し、HOMEに戻る', 'goto' => '/chat/index'],
            ],
            'multiple' => false
        ],
        'burube-natu' => [
            'message' => "あなたのパーソナルカラーはブルベ夏です。",
            'options' => [
                ['display' => '診断を終了し、HOMEに戻る', 'goto' => '/chat/index'],
            ],
            'multiple' => false
        ],
        'iebe-haru' => [
            'message' => "あなたのパーソナルカラーはイエベ春です。",
            'options' => [
                ['display' => '診断を終了し、HOMEに戻る', 'goto' => '/chat/index'],
            ],
            'multiple' => false
        ],
        // ここまでパーソナルカラー診断ーーーーーーーーーーーーーーーーー
    ];


    public function index(Request $request)
    {
        // トップ画面に来たら、セッション内の回答を削除
        $request->session()->forget(self::SESSION_KEY_ANSWERS);

        return $this->chat($request, 'index');
    }

    // 'color-select' => [
    //     'message' => "どんな色がお好みですか？",
    //     'options' => [
    //         ['display' => 'レッド系', 'goto' => '/chat/brand-select'],
    //         ['display' => 'ピンク系', 'goto' => '/chat/brand-select'],
    //         ['display' => 'ブラウン系', 'goto' => '/chat/brand-select'],
    //         ['display' => 'ローズ・ワイン系', 'goto' => '/chat/brand-select'],
    //         ['display' => 'ベージュ系', 'goto' => '/chat/brand-select'],
    //         ['display' => 'オレンジ系', 'goto' => '/chat/brand-select'],
    //     ],
    //     'multiple' => true //複数選択可にする
    // ],

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

        if(!$multiple){
            return view('singleanswer', compact('message', 'options', 'multiple', 'currentMessage'));
        }else{
            $goto = $thisMessage['goto'];
            return view('multipleanswer', compact('message', 'options', 'currentMessage', 'goto'));
        }
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

        /**
     * おすすめ商品の表示
     *
     * @param Request $request リクエスト
     * @param 'impression'|'scene' $from 遷移元
     * @return void
     */
    public function osusume(Request $request, $from, $colorCode = null)
    {
        $params = new OsusumeParams();

        if ($colorCode) {
            $message = '似た色を持つ商品をおすすめします。';
            $params->colors = [new RgbColor($colorCode)];
        } else {
            // ここで、$fromによって、おすすめの商品を取得する処理を書く
            switch ($from) {
                case 'impression':
                    // 印象によっておすすめの商品を取得
                    switch ($request->answer) {
                        case 'ゴージャス、華やか':
                            // レッド系、ローズワイン系
                            $message = '華やかなレッド系がおすすめです。';
                            $params->colors = [new RgbColor('#D7514D'), new RgbColor('#B0737B')];
                            break;
                        case '女性らしい、スイート':
                            // ピンク系
                            $message = 'キュートで可愛らしいピンク系がおすすめです。';
                            $params->colors = [new RgbColor('#DD609A')];
                            break;
                        case 'クール、知的':
                            // ベージュ系、ブラウン系
                            $message = '知的でナチュラルなベージュ系がおすすめです。';
                            $params->colors = [new RgbColor('#CF9D5E'), new RgbColor('#A06757')];
                            break;
                        case 'ヘルシー、はつらつ':
                            // オレンジ系
                            $message = 'アクティブではつらつとしたオレンジ系がおすすめです。';
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
                            $message = '華やかなレッド系がおすすめです。';
                            $params->colors = [new RgbColor('#D7514D'), new RgbColor('#B0737B')];
                            break;
                        case 'デート・婚活':
                            // ピンク系
                            $message = 'キュートで可愛らしいピンク系がおすすめです。';
                            $params->colors = [new RgbColor('#DD609A')];
                            break;
                        case '就職活動・証明写真':
                            // ベージュ系、ブラウン系
                            $message = '知的でナチュラルなベージュ系がおすすめです。';
                            $params->colors = [new RgbColor('#CF9D5E'), new RgbColor('#A06757')];
                            break;
                        case 'アクティブな日':
                            // オレンジ系
                            $message = 'アクティブではつらつとしたオレンジ系がおすすめです。';
                            $params->colors = [new RgbColor('#EBAB54')];
                            break;
                        default:
                            throw new Exception('不正な選択です。');
                    }
                    // シーンによっておすすめの商品を取得
                    break;
            }
        }

        $products = TProduct::getRecommendations($params);

        return view('osusume', compact('products', 'message'));
    }

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
