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
        Schema::create('m_categories', function (Blueprint $table) {
            $table->comment('カテゴリ');

            $table->integer('category_id')->primary()->comment('取得元のカテゴリID');
            $table->string('category_name')->comment('カテゴリ名');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_categories');
    }
};
