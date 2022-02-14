<?php

namespace App\Models\Common;

use App\Models\Masafr\Masafr;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comments';
    protected $fillable = [
        'type',
        'user_id',
        'masafr_id',
        'subject',
        'wait',
        'wait_subject'
    ];

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function Masafr()
    {
        return $this->belongsTo(Masafr::class, 'masafr_id', 'id');
    }
    public function getTypeAttribute($value)
    {
        return $value ?? '';
    }
    public function getUserIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getMasafrIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getSubjectAttribute($value)
    {
        return $value ?? '';
    }
    public function getWaitAttribute($value)
    {
        return $value ?? '';
    }
    public function getWaitSubjectAttribute($value)
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
