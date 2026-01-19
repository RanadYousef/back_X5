<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes; 
use App\Models\Borrowing;
use App\Models\Review;
use App\Models\BorrowingRequest;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, SoftDeletes; // ✅ إضافة SoftDeletes

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Attributes to append to the model's array form
     */
    protected $appends = ['badges'];

    /**
     * Relationships
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function borrowingRequests()
    {
        return $this->hasMany(BorrowingRequest::class);
    }

    public function borrows()
    {
        return $this->hasMany(Borrowing::class, 'user_id');
    }

    /**
     * Accessors
     */
    public function getBadgesAttribute()
    {
        $badges = [];

        if ($this->borrows_count > 10) {
            $badges[] = 'القارئ النهم';
        }

        if ($this->reviews_count > 5) {
            $badges[] = 'الناقد';
        }

        if ($this->reviews_avg_rating >= 4.5) {
            $badges[] = 'المميز';
        }

        return $badges;
    }

}