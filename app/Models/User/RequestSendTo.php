<?php

namespace App\Models\User;

use App\Models\Masafr\Masafr;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestSendTo extends Model
{
    use HasFactory;

    protected $table = 'request_send_tos';
    protected $fillable = [
        'type',
        'masar_id',
        'requestService_id'
    ];


    public function masafr()
    {
        return $this->belongsTo(Masafr::class, 'masafr_id', 'id');
    }

    public function requestService()
    {
        return $this->belongsTo(RequestService::class, 'requestService_id', 'id');
    }
    public function getTypeAttribute($value)
    {
        return $value ?? '';
    }
    public function getMasarIdAttribute($value)
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
