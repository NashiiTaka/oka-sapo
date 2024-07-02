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
        Schema::create('t_products', function (Blueprint $table) {
            $table->comment('商品情報');

            $table->integer('product_id')->comment('取得元の商品ID')->primary();
            $table->string('product_name')->comment('商品名');
            $table->integer('maker_id')->index()->comment('メーカーID');
            $table->integer('brand_id')->index()->comment('ブランドID');
            $table->integer('category1_id')->index()->comment('カテゴリ1ID');
            $table->integer('category2_id')->nullable()->index()->comment('カテゴリ2ID');
            $table->string('jan_code')->nullable()->comment('JANコード、未設定の場合有り');
            $table->string('img_file_name')->nullable()->comment('画像ファイル名');
            $table->date('release_date')->nullable()->comment('発売日');
            $table->date('release_date_additional')->nullable()->comment('追加発売日');
            $table->float('rating')->nullable()->comment('レビュー点数');
            $table->float('points')->nullable()->comment('評価ポイント');
            $table->integer('review_count')->nullable()->comment('レビュー数');
            $table->boolean('is_best_cosme')->comment('ベストコスメ受賞有無');
            $table->boolean('is_rankin_cosme')->comment('ランキンコスメ受賞有無');
            $table->integer('price_with_tax')->nullable()->comment('税込価格');
            $table->string('net_content')->nullable()->comment('内容量');
            $table->text('description')->nullable()->comment('商品説明');
            $table->text('ingredients')->nullable()->comment('成分');
            $table->text('how_to_use')->nullable()->comment('使用方法');
            $table->text('features')->nullable()->comment('特徴');
            $table->text('caution')->nullable()->comment('注意事項');
            $table->string('buy_url')->nullable()->comment('購入URL');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_products');
    }
};
