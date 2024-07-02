<?php

namespace App\Http\Middleware;

use App\Http\Controllers\ChatController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BeforeChatMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // リクエストのmessageとanswerがあれば、それをセッションに保存
        if($request->message && $request->answer) {
            $answers = $request->session()->get(ChatController::SESSION_KEY_ANSWERS, []);
            $answers[$request->message] = $request->answer;
            $request->session()->put(ChatController::SESSION_KEY_ANSWERS, $answers);
        }

        return $next($request);
    }
}
