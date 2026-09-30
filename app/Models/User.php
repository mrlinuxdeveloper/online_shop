<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'tel',
        'address',
        'password',
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
     public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function couponUsages()
    {
        return $this->hasMany(CouponUsage::class);
    }
    public function adminUser()
{
    return $this->hasOne(AdminUser::class);
}
}

// <?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;

// class User extends Authenticatable
// {
//     use HasFactory, Notifiable;

//     protected $fillable = [
//         'name',
//         'lastname',
//         'email',
//         'tel',
//         'address',
//         'password',
//     ];

//     protected $hidden = [
//         'password',
//         'remember_token',
//     ];

//     protected function casts(): array
//     {
//         return [
//             'password' => 'hashed',
//         ];
//     }

//     public function addresses()
//     {
//         return $this->hasMany(Address::class);
//     }

//     public function carts()
//     {
//         return $this->hasMany(Cart::class);
//     }

//     public function orders()
//     {
//         return $this->hasMany(Order::class);
//     }

//     public function payments()
//     {
//         return $this->hasMany(Payment::class);
//     }

//     public function wishlists()
//     {
//         return $this->hasMany(Wishlist::class);
//     }

//     public function reviews()
//     {
//         return $this->hasMany(Review::class);
//     }

//     public function couponUsages()
//     {
//         return $this->hasMany(CouponUsage::class);
//     }
// }