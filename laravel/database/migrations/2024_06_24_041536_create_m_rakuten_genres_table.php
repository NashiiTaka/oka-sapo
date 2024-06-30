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
        Schema::create('m_rakuten_genres', function (Blueprint $table) {
            $table->comment('楽天ジャンル');

            $table->integer('genre_id')->primary()->comment('楽天ジャンルID');
            $table->string('genre_name', 128)->comment('楽天ジャンル名');
            $table->tinyInteger('genre_level')->comment('ジャンル階層、1:トップカテゴリ、以下、最大5層まで。');
            $table->integer('parent_genre_id')->index()->nullable()->comment('親ジャンルID。トップカテゴリの場合はNULL。');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_rakuten_genres');
    }
};
