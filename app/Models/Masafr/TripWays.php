<?php

namespace App\Models\Masafr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripWays extends Model
{
    use HasFactory;

    protected $table = 'trip_ways';
    protected $fillable = [
        'trip_id',
        'place',
        'time'
    ];
    public function getTripIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getPlaceAttribute($value)
    {
        return $value ?? '';
    }
    public function getTimeAttribute($value)
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
