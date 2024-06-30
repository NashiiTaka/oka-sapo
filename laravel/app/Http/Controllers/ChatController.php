<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\App;
use App\Models\TChat;
use App\Models\TProduct;
use Exception;


class ChatController extends Controller
{
    // Route::get('/best-one-lead', [ChatController::class, 'bestOne']);
    // Route::get('/ask-purpose', [ChatController::class, 'askPurpose']);

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
                ['display' => '良さげなものだったら新しいアイテムが欲しい。', 'goto' => '/chat/price-select'],
                ['display' => '今使用しているものがなくなりそう。', 'goto' => '/chat/price-select'],
                ['display' => '今使用しているものに不満、またはお悩みやトラブルがある。', 'goto' => '/chat/price-select'],
                ['display' => '印象を変えたい。', 'goto' => '/chat/price-select'],
            ],
            'multiple' => false
        ],
        'price-select' => [
            'message' => "１本あたりの予算はどれくらいでしょうか？",
            'options' => [
                ['display' => '1,000円以下', 'goto' => '/chat/scene-select'],
                ['display' => '1,000円〜2,000円', 'goto' => '/chat/scene-select'],
                ['display' => '2,000円〜3,000円', 'goto' => '/chat/scene-select'],
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
                ['display' => 'ゴージャス、華やか', 'goto' => '/chat/gorgeous'],
                ['display' => '女性らしい、スイート', 'goto' => '/chat/sweet'],
                ['display' => 'クール、知的', 'goto' => '/chat/cool'],
                ['display' => 'ヘルシー、はつらつ', 'goto' => '/chat/healthy'],
            ],
            'multiple' => false
        ],
        'scene' => [
            'message' => "どんなシーンで使う予定ですか？",
            'options' => [
                ['display' => '普段使い', 'goto' => '/chat/impression'],
                ['display' => '特別な日', 'goto' => '/chat/how-scene'],
                ['display' => '特別な日', 'goto' => '/chat/how-scene'],
                ['display' => '特別な日', 'goto' => '/chat/how-scene'],
            ],
            'multiple' => false
        ],

    ];


    // Route::get('/', [ChatController::class, 'index']);
    public function index()
    {
        return $this->chat('index');
    }

    // Routeに以下の指定をしておくと、
    // Route::get('/chat/{message}', [ChatController::class, 'chat']);
    //
    // localhost/chat/ask-purpose   => $message 'ask-purpose'
    // localhost/chat/best-one-lead => $message 'best-one-lead'
    // 様になって、このメソッドが呼び出される。
    public function chat(string $message)
    {
        $thisMessage = self::$messages[$message];
        $message = $thisMessage['message'];
        $options = $thisMessage['options'];
        $multiple = $thisMessage['multiple'];

        return view('singleanswer', compact('message', 'options', 'multiple'));
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
