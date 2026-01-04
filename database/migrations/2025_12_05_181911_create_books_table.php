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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            
            $table->string('title');
            $table->text('description');
            $table->text('note');
            $table->string('preview_image');

            $table->integer('price')->nullable();
            $table->boolean('is_published')->default(1);

            
            $table->foreignId('category_id')->nullable()->index()->constrained('categories');
            $table->foreignId('author_id')->nullable()->index()->constrained('authors');
            $table->foreignId('user_id')->nullable()->index()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
