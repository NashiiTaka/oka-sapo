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
        Schema::create('t_valiations', function (Blueprint $table) {
            $table->comment('バリエーション');

            $table->integer('valiation_id')->primary()->comment('バリエーションID');
            $table->integer('product_id')->comment('商品ID')->index();
            $table->string('valiation_name')->comment('バリエーション名');
            $table->string('extension')->nullable()->comment('拡張子、「.」を含まない');
            $table->integer('r')->comment('RGBのR値');
            $table->integer('g')->comment('RGBのG値');
            $table->integer('b')->comment('RGBのB値');
            $table->string('hex_color_code')->comment('16進数カラーコード「#FFEECC」の形式');
            $table->boolean('is_active')->comment('現在有効なバリエーション化どうか');
            $table->string('pre_hex_color_code')->comment('カラーチェックで変更された場合、変更前のカラーが入る。')->nullable();
            $table->timestamp('checked_at')->comment('カラーチェックを実施した日時')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_valiations');
    }
};
