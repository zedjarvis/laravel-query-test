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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('author')->nullable();
            $table->string('title', 300)->nullable();
            $table->string('slug', 300)->nullable();
            $table->mediumText('description')->nullable();
            $table->mediumText('image_url')->nullable();
            $table->mediumText('article_url')->nullable();
            $table->text('content')->nullable();
            $table->string('published')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
