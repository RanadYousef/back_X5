<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('book_id')
                ->constrained('books')
                ->cascadeOnDelete();

            $table->timestamp('borrowed_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            // 'borrowed' = Book is with the user.
            // 'returned' = Book is back in the library.
            // 'overdue'  = User is late returning the book.
            $table->string('status')->default('borrowed');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
