<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisingPlaces extends Model
{
    use HasFactory;

    protected $table = 'advertising_places';
    protected $fillable = ['advertising_id', 'type', 'place'];

    public function advertising()
    {
        return $this->belongsTo(Advertising::class, 'advertising_id', 'id');
    }
    public function getTypeAttribute($value)
    {
        return $value ?? '';
    }
    public function getPlaceAttribute($value)
    {
        return $value ?? '';
    }
    public function getAdvertisingIdAttribute($value)
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
