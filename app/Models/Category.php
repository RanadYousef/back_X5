<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name']; // Allow mass assignment for 'name'

    // One-to-Many relationship: A category can have many books
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
