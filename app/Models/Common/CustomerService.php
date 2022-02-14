<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerService extends Model
{
    use HasFactory;
    protected $table = 'customers_service';
    protected $fillable = [
        'name',
        'email',
        'title',
        'body',
        'attachment'
    ];

    public function getAttachmentAttribute($value)
    {
        $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return ($value == null ? '' : $actual_link . 'images/customers_service/' . $value);
    }
    public function getNameAttribute($value)
    {
        return $value ?? '';
    }
    public function getEmailAttribute($value)
    {
        return $value ?? '';
    }
    public function getTitleAttribute($value)
    {
        return $value ?? '';
    }
    public function getBodyAttribute($value)
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
