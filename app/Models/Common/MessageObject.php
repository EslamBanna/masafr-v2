<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageObject extends Model
{
    use HasFactory;

    protected $table = 'message_objects';
    protected $fillable = [
        'message_id',
        'sender_type',
        'subject',
        'attach',
        'private_msg',
        'code',
        'user_seen',
        'masafr_seen'
    ];


    public function Message()
    {
        return $this->belongsTo(Message::class, 'message_id', 'id');
    }

    public function getAttachAttribute($value)
    {
        $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return ($value == null ? '' : $actual_link . 'images/messages/' . $value);
    }
    public function getMessageIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getSenderTypeAttribute($value)
    {
        return $value ?? '';
    }
    public function getSubjectAttribute($value)
    {
        return $value ?? '';
    }
    public function getPrivateMsgAttribute($value)
    {
        return $value ?? '';
    }
    public function getCodeAttribute($value)
    {
        return $value ?? '';
    }
    public function getUserSeenAttribute($value)
    {
        return $value ?? '';
    }
    public function getMasafrSeenAttribute($value)
    {
        return $value ?? '';
    }
    public function getAttribute($value)
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
