<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertising extends Model
{
    use HasFactory;


    protected $table = 'advertisings';
    protected $fillable = [
        'subject',
        'link',
        'site_after_announcement',
        'all_days',
        // 'all_users_masafrs',
        'appear_time',
        'start_date',
        'end_date',
        'daily_repeat',
        'active',
        'image',
        'animation_type',
        'person_target',
        'user_appear',
        'masafr_appear'
    ];

    public function getImageAttribute($value)
    {
        $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return ($value == null ? '' : $actual_link . 'images/advertisings/' . $value);
    }

    public function users()
    {
        return $this->hasMany(AdvertisingUser::class, 'advertising_id', 'id');
    }
    public function days()
    {
        return $this->hasMany(AdvertisingDay::class, 'advertising_id', 'id');
    }

    public function places()
    {
        return $this->hasMany(AdvertisingPlaces::class, 'advertising_id', 'id');
    }

    public function getSubjectAttribute($value)
    {
        return $value ?? '';
    }
    public function getLinkAttribute($value)
    {
        return $value ?? '';
    }
    public function getSiteAfterAnnouncementAttribute($value)
    {
        return $value ?? '';
    }
    public function getAllDaysAttribute($value)
    {
        return $value ?? '';
    }
    public function getAppearTimeAttribute($value)
    {
        return $value ?? '';
    }
    public function getDailyRepeatAttribute($value)
    {
        return $value ?? '';
    }
    public function getActiveAttribute($value)
    {
        return $value ?? '';
    }
    public function getAnimationTypeAttribute($value)
    {
        return $value ?? '';
    }
    public function getPersonTargetAttribute($value)
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
