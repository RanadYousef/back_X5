<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Borrowing;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    protected $appends = ['badges'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
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
