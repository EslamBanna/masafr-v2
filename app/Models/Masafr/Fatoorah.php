<?php

namespace App\Models\Masafr;

use App\Models\Common\RequestTrip;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fatoorah extends Model
{
    use HasFactory;

    protected $table = 'fatoorahs';
    protected $fillable = [
        'fatoorah_list_id',
        'subject',
        'value',
        'is_fee_insurance'
    ];

    public function fatoorahList()
    {
        return $this->belongsTo(FatoorahList::class, 'fatoorah_list_id', 'id');
    }
    public function getFatoorahListIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getSubjectAttribute($value)
    {
        return $value ?? '';
    }
    public function getValueAttribute($value)
    {
        return $value ?? '';
    }
    public function getIsFeeInsuranceAttribute($value)
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
