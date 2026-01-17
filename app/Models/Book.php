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
}
  
