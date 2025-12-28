<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowRequest extends Model
{
    protected $fillable=['user_id',
    'book_id',
    
];
public function book(){
    return $this->belongsTo(Book::class);
}

public function user(){
    return $this->belongsTo(User::class);

}

}
