<?php

namespace App\Models\Admin;

use App\Models\User\CoponUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Copon extends Model
{
    use HasFactory;

    protected $table = 'copons';
    protected $fillable = [
        'gift_picture',
        'copon_name',
        'copon_start_date',
        'copon_end_date',
        'amount',
        'value',
        'copon_type',
        'used',
        'copon_full_amount_err',
        'not_exsist_copon_err',
        'has_used_copon_before_err',
        'copon_success',
        'link',
        'attach'
    ];

    public function GiftUsers()
    {
        return $this->hasMany(GiftCoponUser::class, 'copon_id', 'id');
    }

    public function CoponUsers()
    {
        return $this->hasMany(CoponUser::class, 'copon_id', 'id');
    }

    public function getGiftPictureAttribute($value)
    {
        $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return ($value == null ? '' : $actual_link . 'images/copons/' . $value);
    }

    public function getAttachAttribute($value)
    {
        $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return ($value == null ? '' : $actual_link . 'images/copons/' . $value);
    }

    public function getCoponNameAttribute($value)
    {
        return $value ?? '';
    }
    public function getCoponStartDateAttribute($value)
    {
        return $value ?? '';
    }
    public function getCoponEndDateAttribute($value)
    {
        return $value ?? '';
    }
    public function getAmountAttribute($value)
    {
        return $value ?? '';
    }
    public function getValueAttribute($value)
    {
        return $value ?? '';
    }
    public function getCoponTypeAttribute($value)
    {
        return $value ?? '';
    }
    public function getUsedAttribute($value)
    {
        return $value ?? '';
    }
    public function getCoponFullAmountErrAttribute($value)
    {
        return $value ?? '';
    }
    public function getNotExsistCoponErrAttribute($value)
    {
        return $value ?? '';
    }
    public function getHasUsedCoponBeforeErrAttribute($value)
    {
        return $value ?? '';
    }
    public function getCoponSuccessAttribute($value)
    {
        return $value ?? '';
    }
    public function getLinkAttribute($value)
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
