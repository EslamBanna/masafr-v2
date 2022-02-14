<?php

namespace App\Models\Common;

use App\Models\Admin\NotificationOrMailPerson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotificationOrEmail extends Model
{
    use HasFactory;

    protected $table = 'admin_notifications_or_email';
    protected $fillable = [
        'type',
        'send_by',
        'type_of_template',
        'subject',
        'title',
        // 'seen_time'
    ];

    public function persons()
    {
        return $this->hasMany(NotificationOrMailPerson::class, 'notfication_or_mail_id', 'id');
    }

    public function personUser()
    {
        return $this->hasOne(NotificationOrMailPerson::class, 'notfication_or_mail_id', 'id');
    }

    public function personMasafr()
    {
        return $this->hasOne(NotificationOrMailPerson::class, 'notfication_or_mail_id', 'id');
    }
    public function getTypeAttribute($value)
    {
        return $value ?? '';
    }
    public function getSendByAttribute($value)
    {
        return $value ?? '';
    }
    public function getTypeOfTemplateAttribute($value)
    {
        return $value ?? '';
    }
    public function getSubjectAttribute($value)
    {
        return $value ?? '';
    }
    public function getTitleAttribute($value)
    {
        return $value ?? '';
    }
    // public function getSeenTimeAttribute($value)
    // {
    //     return $value ?? '';
    // }
    public function getCreatedAtAttribute($value)
    {
        return $value ?? '';
    }
    public function getUpdatedAtAttribute($value)
    {
        return $value ?? '';
    }
}
