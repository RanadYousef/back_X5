<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = ['name']; // Allow mass assignment for 'name'

    // One-to-Many relationship: A category can have many books
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
