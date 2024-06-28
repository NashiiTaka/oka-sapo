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
            $table->id(); 

            $table->integer('product_id')->unique()->index();
            $table->string('product_name');
            $table->integer('maker_id')->index();
            $table->integer('brand_id')->index();
            $table->integer('category1_id')->index();
            $table->integer('category2_id')->nullable()->index();
            $table->string('jan_code')->nullable();
            $table->date('release_date')->nullable();
            $table->date('release_date_additional')->nullable();
            $table->float('rating')->nullable();
            $table->float('points')->nullable();
            $table->integer('review_count')->nullable();
            $table->boolean('is_best_cosme');
            $table->boolean('is_rankin_cosme');
            $table->integer('price_with_tax')->nullable();
            $table->string('net_content')->nullable();
            $table->text('description')->nullable();
            $table->text('ingredients')->nullable();
            $table->text('how_to_use')->nullable();
            $table->text('features')->nullable();
            $table->text('caution')->nullable();
            $table->string('buy_url')->nullable();

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
