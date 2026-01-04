<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    const ROLE_ADMIN = 1;
    const ROLE_USER = 0;

    public static function getRoles()
    {
        return [
            self::ROLE_ADMIN => 'Адмін',
            self::ROLE_USER => 'Користувач',
        ];
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'surname',
        'telephone',
        'role',
    ];

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

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'recipent_id', 'id')->where('is_published', 1);
    }

    public function reviewsSent()
    {
        return $this->hasMany(Review::class, 'sender_id', 'id');
    }

    public function chats()
    {
        return Chat::where('sender_id', $this->id)
            ->orWhere('recipient_id', $this->id);
    }
}
