<?php

namespace App\Models\Masafr;

use App\Models\Common\Comment;
use App\Models\Common\Complain;
use App\Models\Common\Message;
use App\Models\Common\UpdateQeueu;
use App\Models\User\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masafr extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'masafr';
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'id_photo',
        'gender',
        'country_code',
        'phone',
        'validation_code',
        'active',
        'active_try',
        'last_try_verify',
        'last_send_verify_code',
        'email_verified_at',
        'national_id_number',
        'nationality',
        'car_name',
        'car_model',
        'car_number',
        'car_image_east',
        'car_image_west',
        'car_image_north',
        'driving_license_photo',
        // 'trips_count',
        // 'bargains_count',
        // 'negative_points_count',
        'sms_notifications',
        'email_notifications',
        'balance',
        'is_verified',
        'trust',
        'decision_maker',
        // 'nationality'
    ];
    protected $hidden = ['password'];

    public function getPhotoAttribute($value)
    {
        $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return ($value == null ? '' : $actual_link . 'images/masafrs/' . $value);
    }

    public function getIdPhotoAttribute($value)
    {
        $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return ($value == null ? '' : $actual_link . 'images/masafrs_id/' . $value);
    }

    public function getCarImageEastAttribute($value)
    {
        $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return ($value == null ? '' : $actual_link . 'images/cars/' . $value);
    }
    public function getCarImageWestAttribute($value)
    {
        $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return ($value == null ? '' : $actual_link . 'images/cars/' . $value);
    }
    public function getCarImageNorthAttribute($value)
    {
        $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return ($value == null ? '' : $actual_link . 'images/cars/' . $value);
    }

    public function getDrivingLicensePhotoAttribute($value)
    {
        $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return ($value == null ? '' : $actual_link . 'images/driving_licenses/' . $value);
    }
    public function getNameAttribute($value)
    {
        return $value ?? '';
    }
    public function getEmailAttribute($value)
    {
        return $value ?? '';
    }
    public function getGenderAttribute($value)
    {
        return $value ?? '';
    }
    public function getCountryCodeAttribute($value)
    {
        return $value ?? '';
    }
    public function getPhoneAttribute($value)
    {
        return $value ?? '';
    }
    public function getValidationCodeAttribute($value)
    {
        return $value ?? '';
    }
    public function getActiveAttribute($value)
    {
        return $value ?? '';
    }
    public function getActiveTryAttribute($value)
    {
        return $value ?? '';
    }
    public function getLastTryVerifyAttribute($value)
    {
        return $value ?? '';
    }
    public function getLastSendVerifyCodeAttribute($value)
    {
        return $value ?? '';
    }
    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ?? '';
    }
    public function getNationalIdNumberAttribute($value)
    {
        return $value ?? '';
    }
    public function getNationalityAttribute($value)
    {
        return $value ?? '';
    }
    public function getCarNameAttribute($value)
    {
        return $value ?? '';
    }
    public function getCarModelAttribute($value)
    {
        return $value ?? '';
    }
    public function getCarNumberAttribute($value)
    {
        return $value ?? '';
    }
    public function getSmsNotificationsAttribute($value)
    {
        return $value ?? '';
    }
    public function getEmailNotificationsAttribute($value)
    {
        return $value ?? '';
    }
    public function getBalanceAttribute($value)
    {
        return $value ?? 0;
    }
    public function getIsVerifiedAttribute($value)
    {
        return $value ?? '';
    }
    public function getTrustAttribute($value)
    {
        return $value ?? '';
    }
    public function getDecisionMakerAttribute($value)
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

    public function user()
    {
        return $this->belongsTo(User::class, 'phone', 'phone');
    }

    public function complains()
    {
        return $this->hasMany(Complain::class, 'masafr_id', 'id');
    }

    public function trips()
    {
        return $this->hasMany(Trips::class, 'masafr_id', 'id');
    }

    public function chats()
    {
        return $this->hasMany(Message::class, 'masafr_id', 'id');
    }

    public function updateQueue()
    {
        return $this->hasMany(UpdateQeueu::class, 'person_id', 'id')->where('type', 1);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'masafr_id', 'id');
    }

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

}
