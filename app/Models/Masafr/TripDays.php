<?php

namespace App\Models\Masafr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripDays extends Model
{
    use HasFactory;

    protected $table = 'trip_days';
    protected $fillable = [
        'trip_id',
        'trip_day'
    ];
    public function getTripIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getTripDayAttribute($value)
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
