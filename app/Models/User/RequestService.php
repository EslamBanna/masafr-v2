<?php

namespace App\Models\User;

use App\Models\Admin\RequestCategorie;
use App\Models\Common\Complain;
use App\Models\Common\Message;
use App\Models\Common\Notification;
use App\Models\Common\RequestTrip;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestService extends Model
{
    use HasFactory;

    protected $table = 'request_services';
    protected $fillable = [
        'user_id',
        'type_of_trips',
        'type_of_services',
        'from_place',
        'from_longitude',
        'from_latitude',
        'to_place',
        'to_longitude',
        'to_latitude',
        'max_day',
        'delivery_to',
        'photo',
        'description',
        'only_women',
        'have_insurance',
        'insurance_value',
        // 'insurance_payed',
        'website_service',
        'number_of_passengers',
        'type_of_car',
        // 'on_progress',
        // 'current_status',
        // 'discounts',
        // 'hold_money',
        'active'
    ];

    public function getUserIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getTypeOfTripsAttribute($value)
    {
        return $value ?? '';
    }
    public function getTypeOfServicesAttribute($value)
    {
        return $value ?? '';
    }
    public function getFromPlaceAttribute($value)
    {
        return $value ?? '';
    }
    public function getFromLongitudeAttribute($value)
    {
        return $value ?? '';
    }
    public function getFromLatitudeAttribute($value)
    {
        return $value ?? '';
    }
    public function getToPlaceAttribute($value)
    {
        return $value ?? '';
    }
    public function getToLongitudeAttribute($value)
    {
        return $value ?? '';
    }
    public function getToLatitudeAttribute($value)
    {
        return $value ?? '';
    }
    public function getMaxDayAttribute($value)
    {
        return $value ?? '';
    }
    public function getDeliveryToAttribute($value)
    {
        return $value ?? '';
    }
    public function getDescriptionAttribute($value)
    {
        return $value ?? '';
    }
    public function getOnlyWomenAttribute($value)
    {
        return $value ?? '';
    }
    public function getHaveInsuranceAttribute($value)
    {
        return $value ?? '';
    }
    public function getInsuranceValueAttribute($value)
    {
        return $value ?? '';
    }
    public function getWebsiteServiceAttribute($value)
    {
        return $value ?? '';
    }
    public function getNumberOfPassengersAttribute($value)
    {
        return $value ?? '';
    }
    public function getTypeOfCarAttribute($value)
    {
        return $value ?? '';
    }
    public function getActiveAttribute($value)
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
        return ($value == null ? '' : $actual_link . 'images/request_services/' . $value);
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'related_request_service', 'id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'related_request_service', 'id');
    }

    public function trips()
    {
        return $this->belongsToMany(RequestService::class, 'request_trips', 'request_id', 'trip_id');
    }

    public function requestTrip()
    {
        return $this->hasMany(RequestTrip::class, 'request_id', 'id');
    }

    public function requestSendTo()
    {
        return $this->hasMany(RequestSendTo::class, 'requestService_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(RequestCategorie::class, 'type_of_trips', 'id');
    }

    public function complains()
    {
        return $this->hasMany(Complain::class, 'related_request_service', 'id');
    }
}
