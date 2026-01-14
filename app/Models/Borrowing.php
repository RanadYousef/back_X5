<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_at',
        'due_date',
        'returned_at',
        'status',
    ];

    /**
     * Dates to be treated as Carbon instances.
     */
    protected $casts = [
        'borrowed_at' => 'datetime',
        'returned_at' => 'datetime',
        'due_date' => 'datetime',
    ];


    /*
      العلاقة مع المستخدم
      user (1) -> borrowings (*)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
      العلاقة مع الكتاب
      book (1) -> borrowings (*)
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
