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
        Schema::create('t_makers', function (Blueprint $table) {
            $table->comment('メーカー');
            $table->id();

            $table->integer('maker_id')->unique()->comment('取得元のメーカーID');
            $table->string('maker_name')->comment('メーカー名');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_makers');
    }
};
