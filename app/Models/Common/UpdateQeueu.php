<?php

namespace App\Models\Common;

use App\Models\Admin\Admin;
use App\Models\Masafr\Masafr;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdateQeueu extends Model
{
    use HasFactory;


    protected $table = 'update_qeueus';

    protected $fillable = [
        'person_id',
        'type',
        'gender',
        'name',
        'email',
        'password',
        'id_photo',
        'national_id_number',
        'admin_response',
        'reason',
        'update_type',
        'decision_maker',
        'nationality',
        'phone'
    ];

    protected $hidden = ['password'];
    public function getIdPhotoAttribute($value)
    {
        $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return ($value == null ? '' : $actual_link . 'images/' . ($this->type == 0 ? 'users_id' : 'masafrs_id') . '/' . $value);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'person_id', 'id');
    }

    public function masafr()
    {
        return $this->belongsTo(Masafr::class, 'person_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'decision_maker', 'id');
    }

    public function getPersonIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getTypeAttribute($value)
    {
        return $value ?? '';
    }
    public function getGenderAttribute($value)
    {
        return $value ?? '';
    }
    public function getNameAttribute($value)
    {
        return $value ?? '';
    }
    public function getEmailAttribute($value)
    {
        return $value ?? '';
    }
    public function getNationalIdNumberAttribute($value)
    {
        return $value ?? '';
    }
    public function getAdminResponseAttribute($value)
    {
        return $value ?? '';
    }
    public function getReasonAttribute($value)
    {
        return $value ?? '';
    }
    public function getUpdateTypeAttribute($value)
    {
        return $value ?? '';
    }
    public function getDecisionMakerAttribute($value)
    {
        return $value ?? '';
    }
    public function getNationalityAttribute($value)
    {
        return $value ?? '';
    }
    public function getPhoneAttribute($value)
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
