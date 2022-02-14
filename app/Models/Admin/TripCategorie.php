<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripCategorie extends Model
{
    use HasFactory;

    protected $table = 'trip_categories';
    protected $fillable = [
        'categorie_name',
        'photo',
        'title',
        'only_saudi',
        'active',
        'special_dlivery',
        'two_place',
        'weekly',
        'counter'

    ];

    public function subsections()
    {
        return $this->hasMany(CategorieTripSubsection::class, 'categorie_id', 'id');
    }

    public function getPhotoAttribute($value)
    {
        $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return ($value == null ? '' : $actual_link . 'images/main_trip_categories/' . $value);
    }
    public function getCategorieNameAttribute($value)
    {
        return $value ?? '';
    }
    public function getTitleAttribute($value)
    {
        return $value ?? '';
    }
    public function getOnlySaudiAttribute($value)
    {
        return $value ?? '';
    }
    public function getActiveAttribute($value)
    {
        return $value ?? '';
    }
    public function getSpecialDliveryAttribute($value)
    {
        return $value ?? '';
    }
    public function getTwoPlaceAttribute($value)
    {
        return $value ?? '';
    }
    public function getWeeklyAttribute($value)
    {
        return $value ?? '';
    }
    public function getCounterAttribute($value)
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
