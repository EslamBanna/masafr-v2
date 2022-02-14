<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Common\Complain;

class ComplainList extends Model
{
    use HasFactory;
    protected $table = 'complain_lists';
    protected $fillable = [
        'type',
        'subject',
        'attach',
        'complain_id'
    ];

    public function Complain()
    {
        return $this->belongsTo(Complain::class, 'complain_id', 'id');
    }

    public function getAttachAttribute($value)
    {
        $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return ($value == null ? '' : $actual_link . 'images/complains/' . $value);
    }
    public function getTypeAttribute($value)
    {
        return $value ?? '';
    }
    public function getSubjectAttribute($value)
    {
        return $value ?? '';
    }
    public function getComplainIdAttribute($value)
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
