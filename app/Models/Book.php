<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;
    protected $fillable=[
      'category_id',
      'title',
      'author',
      'description',
      'publish_year',
     'cover_image',
     'language',
     'copies_number',
    ];

   protected $appends = [
    'average_rating',
    'ratings_count',
  ];

public function category(){
    return $this->belongsTo(Category::class);
  }
  public function borrowRequests(){
     return $this->hasMany(BorrowingRequest::class);
  }
  public function borrows(){
    return $this->hasMany(Borrowing::class);
  }

   public function reviews(){
    return $this->hasMany(Review::class);
  }

    /**
     * Only approved reviews for rating calculations.
     */
   public function approvedReviews()
   {
    return $this->reviews()->where('status', 'approved');
   }

    /**
     * Computed attribute: total count of approved ratings.
     */
   public function getRatingsCountAttribute()
   {
     return $this->approvedReviews()->count();
     
   }

   /**
    * Computed attribute: average rating from approved reviews.
    */
   public function getAverageRatingAttribute()
   {
    return round($this->approvedReviews()->avg('rating') ?? 0, 1);
   }

}
  
