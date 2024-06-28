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
            $table->id();

            $table->integer('reviewer_id')->nullable();
            $table->string('reviewer_name');
            $table->integer('followers_level')->nullable();
            $table->integer('reviews_count');
            $table->integer('reviewer_age');
            $table->string('reviewer_skin_type');
            $table->integer('product_id');
            $table->string('method_of_acquisition')->nullable();
            $table->float('rating')->nullable();
            $table->string('effects')->nullable();
            $table->text('review_content');
            $table->timestamp('published_at');
            
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
