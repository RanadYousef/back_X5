<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Book
 *
 * Represents a book entity in the system.
 *
 * This model handles:
 * - Book basic information
 * - Relationships with categories, borrowings, and reviews
 * - Rating calculations based on approved reviews only
 *
 * Soft deletes are enabled.
 */
class Book extends Model
{
  use SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'category_id',
    'title',
    'author',
    'description',
    'publish_year',
    'cover_image',
    'language',
    'copies_number',
  ];

  /**
   * Accessors that should be appended to the model's array and JSON form.
   *
   * @var array<int, string>
   */
  protected $appends = [
    'average_rating',
    'ratings_count',
  ];

  /**
   * Get the category that this book belongs to.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function category()
  {
    return $this->belongsTo(Category::class);
  }
  /**
   * Get all borrowing requests associated with this book.
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function borrowRequests()
  {
    return $this->hasMany(BorrowingRequest::class);
  }
  /**
   * Get all borrow records for this book.
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function borrows()
  {
    return $this->hasMany(Borrowing::class);
  }

  /**
   * Get all reviews submitted for this book.
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function reviews()
  {
    return $this->hasMany(Review::class);
  }

  /**
   * Get only approved reviews for this book.
   *
   * Used for calculating rating statistics.
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function approvedReviews()
  {
    return $this->reviews()->where('status', 'approved');
  }

  /**
   * Get the total number of approved ratings for the book.
   *
   * @return int
   */
  public function getRatingsCountAttribute()
  {
    return $this->approvedReviews()->count();
  }

  /**
   * Get the average rating of the book based on approved reviews.
   *
   * @return float
   */
  public function getAverageRatingAttribute()
  {
    return round($this->approvedReviews()->avg('rating') ?? 0, 1);
  }
}