<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateBooksTable
 *
 * Migration responsible for creating the "books" table.
 *
 * This table stores all book-related information such as:
 * - Title, author, and description
 * - Publishing year and language
 * - Number of available copies
 * - Cover image path
 *
 * The table also supports soft deletes.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the "books" table with all required columns
     * and establishes the relationship with the categories table.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('title');
            $table->string('author');
            $table->text('description');
            $table->year('publish_year');
            $table->string('cover_image')->nullable();
            $table->string('language');
            $table->unsignedInteger('copies_number');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the "books" table from the database.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
