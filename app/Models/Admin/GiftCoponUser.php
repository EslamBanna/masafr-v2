<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCoponUser extends Model
{
    use HasFactory;

    protected $table = 'gift_copon_users';
    protected $fillable = ['phone', 'copon_id'];

    public function copon()
    {
        return $this->belongsTo(Copon::class, 'copon_id', 'id');
    }

    public function getPhoneAttribute($value)
    {
        return $value ?? '';
    }
    public function getCoponIdAttribute($value)
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
