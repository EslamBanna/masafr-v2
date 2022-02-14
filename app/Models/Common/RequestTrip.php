<?php

namespace App\Models\Common;

// use App\Models\Masafr\Fatoorah;

use App\Models\Masafr\FatoorahList;
use App\Models\Masafr\Trips;
use App\Models\User\RequestService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestTrip extends Model
{
    use HasFactory;

    protected $table = 'request_trips';
    protected $fillable = [
        'request_id',
        'trip_id',
        'receipt_code',
        'delivery_code',
        'offer_status',
        'payment_method',
        'user_mark',
        'user_feedback',
        'masafr_mark',
        'masafr_feedback',
        'decision_maker',
        'reasons',
        'current_status',
        'discounts',
        'chat_id',
        'website_service',
        'insurance_hold',
        'cancel_type'
    ];

    public function fatoorahList()
    {
        return $this->hasMany(FatoorahList::class, 'request_trip_id', 'id');
    }

    public function request()
    {
        return $this->belongsTo(RequestService::class, 'request_id', 'id');
    }

    public function trip()
    {
        return $this->belongsTo(Trips::class, 'trip_id', 'id');
    }

    public function message()
    {
        return $this->belongsTo(Message::class, 'chat_id', 'id');
    }

    public function getRequestIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getTripIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getReceiptCodeAttribute($value)
    {
        return $value ?? '';
    }
    public function getDeliveryCodeAttribute($value)
    {
        return $value ?? '';
    }
    public function getOfferStatusAttribute($value)
    {
        return $value ?? '';
    }
    public function getPaymentMethodAttribute($value)
    {
        return $value ?? '';
    }
    public function getUserMarkAttribute($value)
    {
        return $value ?? '';
    }
    public function getUserFeedbackAttribute($value)
    {
        return $value ?? '';
    }
    public function getMasafrMarkAttribute($value)
    {
        return $value ?? '';
    }
    public function getMasafrFeedbackAttribute($value)
    {
        return $value ?? '';
    }
    public function getDecisionMakerAttribute($value)
    {
        return $value ?? '';
    }
    public function getReasonsAttribute($value)
    {
        return $value ?? '';
    }
    public function getCurrentStatusAttribute($value)
    {
        return $value ?? '';
    }
    public function getDiscountsAttribute($value)
    {
        return $value ?? '';
    }
    public function getChatIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getWebsiteServiceAttribute($value)
    {
        return $value ?? '';
    }
    public function getInsuranceHoldAttribute($value)
    {
        return $value ?? '';
    }
    public function getCancelTypeAttribute($value)
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
