<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieTripSubsection extends Model
{
    use HasFactory;

    protected $table = 'categorie_trip_subsections';
    protected $fillable = ['categorie_id', 'section_name'];

    public function mainCategorie()
    {
        return $this->belongsTo(TripCategorie::class, 'categorie_id', 'id');
    }

    public function getCategorieIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getSectionNameAttribute($value)
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
