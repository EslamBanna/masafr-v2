<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisingDay extends Model
{
    use HasFactory;

    protected $table = 'advertising_days';
    protected $fillable = ['advertising_id', 'day'];


    public function advertising()
    {
        return $this->belongsTo(Advertising::class, 'advertising_id', 'id');
    }

    public function getDayAttribute($value)
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
