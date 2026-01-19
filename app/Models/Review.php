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
    /**
     * Summary of user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Review>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    
    // Define the review model
}
