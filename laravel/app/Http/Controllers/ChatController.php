<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\App;
use App\Models\TChat;
use App\Models\TProduct;
use Exception;


class ChatController extends Controller
{
    public function index()
    {
        $message = "こんにちは。コスメピシャットへようこそ！\n本日はどのような目的でご来店いただいたのですか？";
        $options = [
            ['display' => '新しいアイテムが欲しい。', 'goto' => 'ask-purpose'],
            ['display' => '良さげなものがあれば買いたい。', 'goto' => 'best-one-lead'],
            ['display' => 'どんな商品があるか情報だけを知りたい。', 'goto' => 'best-one-lead']
        ];
        $multiple = false;

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
