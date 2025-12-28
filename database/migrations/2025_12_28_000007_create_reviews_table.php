<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
    public function up(): void{
        // Create the reviews table
    Schema::create('reviews', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('book_id')->constrained()->cascadeOnDelete();

    $table->unsignedTinyInteger('rating');
    $table->string('status');
    $table->text('comment')->nullable();

    $table->timestamps();
    $table->softDeletes();
    // Ensure a user can only leave one review per book
    $table->unique(['user_id', 'book_id']);
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
