<?php

namespace App\Models\Common;

use App\Models\Masafr\Masafr;
use App\Models\User\RequestService;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $fillable = [
        'user_id',
        'masafr_id',
        'related_trip',
        'related_request_service'
    ];

    public function MessageObjects()
    {
        return $this->hasMany(MessageObject::class, 'message_id', 'id');
    }

    public function LastMessageObjects()
    {
        return $this->hasOne(MessageObject::class, 'message_id', 'id')->latest();
    }

    public function MessageNotSeen()
    {
        return $this->hasMany(MessageObject::class, 'message_id', 'id')
        ->where('user_seen',0)
        ->orWhere('masafr_seen',0);
    }

    public function attachedChat(){
        return $this->hasMany(MessageObject::class,'message_id','id')->where('attach', '!=',null);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function masafr()
    {
        return $this->belongsTo(Masafr::class, 'masafr_id', 'id');
    }

    // public function requestService()
    // {
    //     return $this->belongsTo(RequestService::class, 'related_request_service', 'id');
    // }

    public function complain()
    {
        return $this->hasOne(Complain::class, 'related_chat', 'id');
    }

    public function pronunciationStatements()
    {
        return $this->hasMany(PronunciationStatement::class, 'chat_id', 'id');
    }

    public function requestTrip()
    {
        return $this->hasMany(RequestTrip::class, 'chat_id', 'id');
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
