<?php

namespace App\Models\Common;

use App\Models\User\RequestService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $fillable = [
        'person_id',
        'type',
        'subject',
        'target_code',
        'related_trip',
        'related_request_service',
        'seen'
    ];

    public function requestService()
    {
        return $this->belongsTo(RequestService::class, 'related_request_service', 'id');
    }
    public function getPersonIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getTypeAttribute($value)
    {
        return $value ?? '';
    }
    public function getSubjectAttribute($value)
    {
        return $value ?? '';
    }
    public function getTargetCodeAttribute($value)
    {
        return $value ?? '';
    }
    public function getRelatedTripAttribute($value)
    {
        return $value ?? '';
    }
    public function getRelatedRequestServiceAttribute($value)
    {
        return $value ?? '';
    }
    public function getSeenAttribute($value)
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
