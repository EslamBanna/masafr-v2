<?php

namespace App\Models\Masafr;

use App\Models\Admin\TripCategorie;
use App\Models\Common\Complain;
use App\Models\Common\Message;
use App\Models\Common\Notification;
use App\Models\Common\RequestTrip;
use App\Models\User\RequestService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trips extends Model
{
    use HasFactory;
    protected $table = 'trips';
    protected $fillable = [
        'masafr_id',
        'type_of_trips',
        'type_of_services',
        'only_women',
        'from_place',
        'from_longitude',
        'from_latitude',
        'to_place',
        'to_longitude',
        'to_latitude',
        'start_date',
        'end_date',
        'description',
        'active',
        // 'negotiations',
        'on_progress'
    ];

    public function getMasafrIdAttribute($value)
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
    public function getOnlyWomenAttribute($value)
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
    public function getStartDateAttribute($value)
    {
        return $value ?? '';
    }
    public function getEndDateAttribute($value)
    {
        return $value ?? '';
    }
    public function getDescriptionAttribute($value)
    {
        return $value ?? '';
    }
    public function getActiveAttribute($value)
    {
        return $value ?? '';
    }
    public function getOnProgressAttribute($value)
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

    public function masafr()
    {
        return $this->belongsTo(Masafr::class, 'masafr_id', 'id');
    }

    public function ways()
    {
        return $this->hasMany(TripWays::class, 'trip_id', 'id');
    }

    public function days()
    {
        return $this->hasMany(TripDays::class, 'trip_id', 'id');
    }

    public function requests()
    {
        return $this->belongsToMany(RequestService::class, 'request_trips', 'trip_id', 'request_id');
    }

    public function trips()
    {
        return $this->belongsToMany(Trips::class, 'request_trips', 'request_id', 'trip_id');
    }

    public function relatedRequests()
    {
        return $this->hasMany(RequestTrip::class, 'trip_id', 'id');
    }

    function masafrMessages()
    {
        return $this->hasManyThrough(Message::class, Masafr::class, 'id', 'masafr_id', 'id', 'id');
    }

    public function tripCategory()
    {
        return $this->belongsTo(TripCategorie::class, 'type_of_trips', 'id');
    }


    public function notifications()
    {
        return $this->hasMany(Notification::class, 'related_trip', 'id');
    }

    public function complains()
    {
        return $this->hasMany(Complain::class, 'related_trip', 'id');
    }

    public function messageRooms()
    {
        return $this->hasMany(Message::class, 'related_trip', 'id');
    }
}
