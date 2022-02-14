<?php

namespace App\Models\Admin;

use App\Models\Common\UpdateQeueu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $table = 'admins';
    protected $fillable = [
        'name',
        'type',
        'password',
        'email',
        'phone',
        'gender',
        'photo'
    ];
    protected $hidden = ['password'];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getPhotoAttribute($value)
    {
        $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return ($value == null ? '' : $actual_link . 'images/admins/' . $value);
    }

    public function trust()
    {
        return $this->hasMany(UpdateQeueu::class, 'decision_maker', 'id');
    }

    public function getNameAttribute($value)
    {
        return $value ?? '';
    }
    public function getTypeAttribute($value)
    {
        return $value ?? '';
    }
    public function getEmailAttribute($value)
    {
        return $value ?? '';
    }
    public function getPhoneAttribute($value)
    {
        return $value ?? '';
    }
    public function getGenderAttribute($value)
    {
        return $value ?? '';
    }

    public function getCreatedAtAttribute($value)
    {
        return $value ?? '';
    }
    public function getUpdatedAtAttribute($value)
    {
        return $value ?? '';
    }
}
