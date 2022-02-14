<?php

namespace App\Models\Admin;

use App\Models\Masafr\Masafr;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RollbackRequest extends Model
{
    use HasFactory;

    protected $table = 'rollback_requests';
    protected $fillable = [
        'user_id',
        'masafr_id',
        'request_id',
        'type',
        'msg',
        'response',
        'reason',
        'escalation',
        'decision',
        'complain_id',
        'decision_maker'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function masafr()
    {
        return $this->belongsTo(Masafr::class, 'masafr_id', 'id');
    }
    public function getUserIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getMasafrIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getRequestIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getTypeAttribute($value)
    {
        return $value ?? '';
    }
    public function getMsgAttribute($value)
    {
        return $value ?? '';
    }
    public function getResponseAttribute($value)
    {
        return $value ?? '';
    }
    public function getEscalationAttribute($value)
    {
        return $value ?? '';
    }
    public function getDecisionAttribute($value)
    {
        return $value ?? '';
    }
    public function getComplainIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getAttribute($value)
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
}
