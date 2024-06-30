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
        Schema::create('t_brands', function (Blueprint $table) {
            $table->comment('ブランド');

            $table->integer('brand_id')->unique()->primary()->comment('取得元のブランドID');
            $table->string('brand_name')->comment('ブランド名');
            $table->integer('maker_id')->comment('メーカーID');
            $table->string('official_site')->nullable()->comment('公式サイトURL');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_brands');
    }
};
