<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApllicationSetting extends Model
{
    use HasFactory;


    protected $table = 'apllication_settings';
    protected $fillable = ['subject', 'value'];

    public function getSubjectAttribute($value)
    {
        return $value ?? '';
    }
    public function getValueAttribute($value)
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
