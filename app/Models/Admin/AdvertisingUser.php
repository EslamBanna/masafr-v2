<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisingUser extends Model
{
    use HasFactory;


    protected $table = 'advertising_users';
    protected $fillable = ['advertising_id', 'phone', 'type'];

    public function advertising()
    {
        return $this->belongsTo(Advertising::class, 'advertising_id', 'id');
    }
    public function getAdvertisingIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getPhoneAttribute($value)
    {
        return $value ?? '';
    }
    public function getTypeAttribute($value)
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
