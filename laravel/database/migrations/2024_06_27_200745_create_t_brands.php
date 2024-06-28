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
            $table->id();

            $table->integer('brand_id')->unique();
            $table->string('brand_name');
            $table->integer('maker_id');
            $table->string('official_site')->nullable();

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
