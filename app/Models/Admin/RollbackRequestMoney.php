<?php

namespace App\Models\Admin;

use App\Models\User\RequestService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RollbackRequestMoney extends Model
{
    use HasFactory;


    protected $table = 'rollback_request_money';
    protected $fillable = [
        'request_id',
        'amount',
        'bank_name',
        'request_time',
        'account_number',
        'description',
        'status',
        'decision_maker',
        'trans_msg'
    ];

    public function request()
    {
        return $this->belongsTo(RequestService::class, 'request_id', 'id');
    }
    public function getRequestIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getAmountAttribute($value)
    {
        return $value ?? '';
    }
    public function getBankNameAttribute($value)
    {
        return $value ?? '';
    }
    public function getRequestTimeAttribute($value)
    {
        return $value ?? '';
    }
    public function getAccountNumberAttribute($value)
    {
        return $value ?? '';
    }
    public function getDescriptionAttribute($value)
    {
        return $value ?? '';
    }
    public function getStatusAttribute($value)
    {
        return $value ?? '';
    }
    public function getDecisionMakerAttribute($value)
    {
        return $value ?? '';
    }
    public function getTransMsgAttribute($value)
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
