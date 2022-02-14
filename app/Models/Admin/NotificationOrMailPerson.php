<?php

namespace App\Models\Admin;

use App\Models\Common\AdminNotificationOrEmail;
use App\Models\Masafr\Masafr;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationOrMailPerson extends Model
{
    use HasFactory;
    protected $table = 'notification_or_mail_people';
    protected $fillable = [
        // 'type',
        'notfication_or_mail_id',
        'person_id',
        'showed',
        'seen_time'
    ];

    public function window()
    {
        return $this->belongsTo(AdminNotificationOrEmail::class, 'notfication_or_mail_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'person_id', 'id');
    }

    public function masafr()
    {
        return $this->belongsTo(Masafr::class, 'person_id', 'id');
    }

    public function getNotficationOrMailIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getPersonIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getShowedAttribute($value)
    {
        return $value ?? '';
    }
    public function getSeenTimeAttribute($value)
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
