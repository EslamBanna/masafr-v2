<?php

namespace App\Models\User;

use App\Models\Admin\Admin;
use App\Models\Admin\NotificationOrMailPerson;
use App\Models\Admin\RollbackRequest;
use App\Models\Common\AdminNotificationOrEmail;
use App\Models\Common\Comment;
use App\Models\Common\Complain;
use App\Models\Common\Message;
use App\Models\Common\MessageObject;
use App\Models\Common\PronunciationStatement;
use App\Models\Common\RequestTrip;
use App\Models\Common\UpdateQeueu;
use App\Models\Masafr\Masafr;
use App\Models\Masafr\Trips;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'id_photo',
        'national_id_number',
        'gender',
        'country_code',
        'phone',
        'validation_code',
        'active',
        'active_try',
        'last_try_verify',
        'last_send_verify_code',
        'email_notifications',
        'balance',
        'is_verified',
        'trust',
        'decision_maker',
        'nationality'
    ];

    public function getNameAttribute($value)
    {
        return $value ?? '';
    }
    public function getEmailAttribute($value)
    {
        return $value ?? '';
    }
    public function getNationalIdNumberAttribute($value)
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
    public function getNationalityAttribute($value)
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
    public function getPhotoAttribute($value)
    {
        $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return ($value == null ? '' : $actual_link . 'images/users/' . $value);
    }

    public function getIdPhotoAttribute($value)
    {
        $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return ($value == null ? '' : $actual_link . 'images/users_id/' . $value);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


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


    public function complains()
    {
        return $this->hasMany(Complain::class, 'user_id', 'id');
    }

    public function requests()
    {
        return $this->hasMany(RequestService::class, 'user_id', 'id');
    }
    public function rollbackRequests()
    {
        return $this->hasMany(RollbackRequest::class, 'user_id', 'id');
    }

    public function masafr()
    {
        return $this->hasOne(Masafr::class, 'phone', 'phone');
    }


    public function trips()
    {
        return $this->hasManyThrough(Trips::class, Masafr::class, 'phone', 'masafr_id', 'phone');
    }

    public function MessageNotSeen()
    {
        return $this->hasManyThrough(MessageObject::class, Message::class, 'id', 'message_id', 'id')->where('user_seen', 0)->orWhere('masafr_seen', 0);
    }

    public function pronunciationStatements()
    {
        return $this->hasManyThrough(PronunciationStatement::class, Message::class, 'id', 'chat_id', 'id');
    }

    public function requestTripNotFinished()
    {
        return $this->hasManyThrough(
            RequestTrip::class,
            RequestService::class,
            'user_id', // Foreign key on the environments table...
            'request_id', // Foreign key on the deployments table...
            'id', // Local key on the projects table...
            'id' // Local key on the environments table...
        );
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'user_id', 'id');
    }

    public function Admin()
    {
        return $this->belongsTo(Admin::class, 'decision_maker', 'id');
    }
    public function copons()
    {
        return $this->hasMany(CoponUser::class, 'user_id', 'id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function updateQueue()
    {
        return $this->hasMany(UpdateQeueu::class, 'person_id', 'id')->where('type', 0);
    }
}
