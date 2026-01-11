<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
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
     * Accessor: Returns average rating from reviews table
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('stars') ?? 0;
    }

    /**
     * Accessor: Returns total borrow count
     */
    public function getBorrowCountAttribute()
    {
        return $this->borrows()->count();
    }
}
  
