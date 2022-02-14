<?php

namespace App\Models\Common;

use App\Models\Masafr\Masafr;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    use HasFactory;

    protected $table = 'complains';
    protected $fillable = [
        'user_id',
        'masafr_id',
        'status',
        'user_negative',
        'masafr_negative',
        'related_trip',
        'related_chat',
        'related_request_service',
        'solved',
        'complainant',
        'reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function masafr()
    {
        return $this->belongsTo(Masafr::class, 'masafr_id', 'id');
    }

    public function complainList()
    {
        return $this->hasMany(ComplainList::class, 'complain_id', 'id');
    }

    public function chat()
    {
        return $this->belongsTo(Message::class, 'related_chat', 'id');
    }

    public function getUserIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getMasafrIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getStatusAttribute($value)
    {
        return $value ?? '';
    }
    public function getUserNegativeAttribute($value)
    {
        return $value ?? '';
    }
    public function getMasafrNegativeAttribute($value)
    {
        return $value ?? '';
    }
    public function getRelatedTripAttribute($value)
    {
        return $value ?? '';
    }
    public function getRelatedChatAttribute($value)
    {
        return $value ?? '';
    }
    public function getRelatedRequestServiceAttribute($value)
    {
        return $value ?? '';
    }
    public function getSolvedAttribute($value)
    {
        return $value ?? '';
    }
    public function getComplainantAttribute($value)
    {
        return $value ?? '';
    }
    public function getReasonAttribute($value)
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
