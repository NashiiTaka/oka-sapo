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
        Schema::create('t_reviews', function (Blueprint $table) {
            $table->comment('レビュー');

            $table->integer('review_id')->primary()->comment('取得元のレビューID');
            $table->integer('reviewer_id')->nullable()->comment('レビュアーID');
            $table->string('reviewer_name')->comment('レビュアー名');
            $table->integer('followers_level')->nullable()->comment('フォロワー数のレベル 〜名以上という意味');
            $table->integer('reviews_count')->comment('レビューアーの総レビュー数');
            $table->integer('reviewer_age')->comment('レビュアーの年齢');
            $table->string('reviewer_skin_type')->comment('レビュアーの肌質');
            $table->integer('product_id')->comment('商品ID');
            $table->string('method_of_acquisition')->nullable()->comment('入手方法');
            $table->float('rating')->nullable()->comment('レビュー点数');
            $table->string('effects')->nullable()->comment('効果、、「|」区切りで複数登録');
            $table->text('review_content')->comment('レビュー内容');
            $table->timestamp('published_at')->comment('レビュー投稿日時');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_reviews');
    }
};
