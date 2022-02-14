<?php

namespace App\Models\Common;

use App\Models\Masafr\Masafr;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PronunciationStatement extends Model
{
    use HasFactory;
    protected $table = 'pronunciation_statements';
    protected $fillable = [
        'sender_type',
        'subject',
        'chat_id'
    ];


    public function chat()
    {
        return $this->belongsTo(Message::class, 'chat_id', 'id');
    }
    public function getSenderTypeAttribute($value)
    {
        return $value ?? '';
    }
    public function getSubjectAttribute($value)
    {
        return $value ?? '';
    }
    public function getChatIdAttribute($value)
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
