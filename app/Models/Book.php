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
     return $this->hasMany(BorrowRequest::class);
  }
  public function borrows(){
    return $this->hasMany(Borrow::class);
  }

   public function reviews(){
    return $this->hasMany(Review::class);
  }
}
  
