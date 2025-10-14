<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermanentlyDeletedMessage extends Model
{
    use HasFactory;

    protected $fillable = ['message_id', 'user_id'];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function permanentlyDeletedMessages()
    {
        return $this->hasMany(PermanentlyDeletedMessage::class);
    }
}

