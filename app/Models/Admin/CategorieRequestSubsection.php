<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieRequestSubsection extends Model
{
    use HasFactory;

    protected $table = 'categorie_request_subsections';
    protected $fillable = ['categorie_id', 'section_name'];

    public function mainCategorie()
    {
        return $this->belongsTo(RequestCategorie::class, 'categorie_id', 'id');
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
