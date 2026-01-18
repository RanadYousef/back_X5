<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Define the review model
class Review extends Model
{
    use HasFactory;
    // Define fillable attributes
   protected $fillable = [
        'user_id',
        'book_id',
        'rating',
        'status',
        'comment',

    ];
    
    // Define relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    //m
    // Define the review model
}
