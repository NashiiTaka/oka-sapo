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
        Schema::create('t_rakuten_products', function (Blueprint $table) {
            $table->comment('楽天商品');

            $table->string('product_id')->primary()->comment('商品ID');
            $table->string('item_name')->comment('商品名');
            $table->string('catchcopy')->nullable()->comment('キャッチコピー');
            $table->text('item_caption')->nullable()->comment('商品説明');
            $table->integer('genre_id')->comment('ジャンルID');
            $table->integer('item_price')->comment('商品価格(税込)(送料別)');
            $table->float('affiliate_rate')->comment('アフィリエイト率');
            $table->float('point_rate')->nullable()->comment('ポイント率');
            $table->timestamp('point_rate_end_time')->nullable()->comment('ポイント率適用終了日時');
            $table->timestamp('point_rate_start_time')->nullable()->comment('ポイント率適用開始日時');
            $table->string('shop_code')->nullable()->comment('ショップコード');
            $table->string('shop_name')->nullable()->comment('ショップ名');
            $table->float('review_average')->nullable()->comment('レビュー平均');
            $table->integer('review_count')->nullable()->comment('レビュー件数');
            $table->text('item_url')->nullable()->comment('商品URL');
            $table->string('item_price_base_field')->nullable()->comment('商品価格基準フィールド');
            $table->integer('item_price_max_1')->nullable()->comment('商品価格最大(税込)(送料別) 1');
            $table->integer('item_price_max_2')->nullable()->comment('商品価格最大(税込)(送料別) 2');
            $table->integer('item_price_max_3')->nullable()->comment('商品価格最大(税込)(送料別) 3');
            $table->integer('item_price_min_1')->nullable()->comment('商品価格最小(税込)(送料別) 1');
            $table->integer('item_price_min_2')->nullable()->comment('商品価格最小(税込)(送料別) 2');
            $table->integer('item_price_min_3')->nullable()->comment('商品価格最小(税込)(送料別) 3');
            $table->boolean('gift_flag')->nullable()->comment('ギフトフラグ');
            $table->boolean('image_flag')->nullable()->comment('画像フラグ');
            $table->boolean('postage_flag')->nullable()->comment('送料フラグ 0：送料込 1：送料別');
            $table->string('ship_overseas_area')->nullable()->comment('海外発送フラグエリア');
            $table->boolean('ship_overseas_flag')->nullable()->comment('海外発送フラグ');
            $table->boolean('shop_of_the_year_flag')->nullable()->comment('年間ショップフラグ');
            $table->string('start_time')->nullable()->comment('開始時間');
            $table->string('end_time')->nullable()->comment('終了時間');
            $table->string('tag_ids')->nullable()->comment('タグID 「|」区切りで複数あり');
            $table->boolean('credit_card_flag')->nullable()->comment('クレジットカードフラグ');
            $table->boolean('tax_flag')->nullable()->comment('消費税フラグ');
            $table->string('asuraku_area')->nullable()->comment('あす楽エリア');
            $table->string('asuraku_closing_time')->nullable()->comment('あす楽締め切り時間');
            $table->boolean('asuraku_flag')->nullable()->comment('あす楽フラグ');
            $table->boolean('availability')->comment('利用可能フラグ');
            $table->text('affiliate_url')->comment('アフィリエイトURL');
            $table->text('medium_image_url_1')->nullable()->comment('商品画像URL(中サイズ) 1');
            $table->text('medium_image_url_2')->nullable()->comment('商品画像URL(中サイズ) 2');
            $table->text('medium_image_url_3')->nullable()->comment('商品画像URL(中サイズ) 3');
            $table->text('small_image_url_1')->nullable()->comment('商品画像URL(小サイズ) 1');
            $table->text('small_image_url_2')->nullable()->comment('商品画像URL(小サイズ) 2');
            $table->text('small_image_url_3')->nullable()->comment('商品画像URL(小サイズ) 3');
            $table->text('shop_url')->nullable()->comment('ショップURL');
            $table->text('shop_affiliate_url')->nullable()->comment('ショップアフィリエイトURL');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_rakuten_products');
    }
};
