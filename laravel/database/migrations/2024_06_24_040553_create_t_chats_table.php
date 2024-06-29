<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('t_chats', function (Blueprint $table) {
            $table->comment('チャットメッセージ');

            $table->id();
            $table->string('thread_id', 128)->index()->comment('ChatGPTのスレッドID');
            $table->string('role', 32)->comment('送信者の役割、user、botなどが入る');
            $table->text('message')->comment('メッセージ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_chats');
    }
};
