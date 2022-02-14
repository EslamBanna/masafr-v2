<?php

namespace App\Models\Admin;

use App\Models\User\RequestService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestCategorie extends Model
{
    use HasFactory;

    protected $table = 'request_categories';
    protected $fillable = [
        'categorie_name',
        'photo',
        'title',
        'only_saudi',
        'payment_method',
        'have_insurance',
        'have_photo',
        'two_places',
        'two_codes',
        'active'
    ];

    public function subsections()
    {
        return $this->hasMany(CategorieRequestSubsection::class, 'categorie_id', 'id');
    }

    public function requestService()
    {
        return $this->hasMany(RequestService::class, 'type_of_services', 'id');
    }

    public function getPhotoAttribute($value)
    {
        $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return ($value == null ? '' : $actual_link . 'images/main_request_categories/' . $value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value ?? '';
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
    public function getPaymentMethodAttribute($value)
    {
        return $value ?? '';
    }
    public function getHaveInsuranceAttribute($value)
    {
        return $value ?? '';
    }
    public function getHavePhotoAttribute($value)
    {
        return $value ?? '';
    }
    public function getTwoPlacesAttribute($value)
    {
        return $value ?? '';
    }
    public function getActiveAttribute($value)
    {
        return $value ?? '';
    }
    public function getCreatedAtAttribute($value)
    {
        return $value ?? '';
    }
}
