<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateBorrowingsTable
 *
 * Migration responsible for creating the "borrowings" table.
 *
 * This table tracks book borrowing operations, including:
 * - Which user borrowed which book
 * - Borrowing date and return date
 * - Current borrowing status (borrowed, returned, overdue)
 *
 * It also enforces referential integrity with users and books.
 */
return new class extends Migration {
    /**
     * Run the migrations.
     *
     * Creates the "borrowings" table which stores
     * all book borrowing records and their statuses.
     *
     * @return void
     */
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
            // Borrowing status:
            // borrowed  -> Book is currently with the user
            // returned  -> Book has been returned to the library
            // overdue   -> Return date has passed
            $table->string('status')->default('borrowed');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the "borrowings" table from the database.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
