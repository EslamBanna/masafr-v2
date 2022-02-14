<?php

namespace App\Models\User;

use App\Models\Admin\Copon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoponUser extends Model
{
    use HasFactory;

    protected $table = 'copon_users';
    protected $fillable = [
        'user_id',
        'copon_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function copon()
    {
        return $this->belongsTo(Copon::class, 'copon_id', 'id');
    }
    public function getUserIdAttribute($value)
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
