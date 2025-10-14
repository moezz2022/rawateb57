<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'subject',
        'body',
        'is_multiple',
    ];


    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'message_group', 'message_id', 'group_id')
            ->withPivot('is_read')
            ->withTimestamps();
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
    public function deletedMessages()
    {
        return $this->hasMany(DeletedMessage::class, 'message_id')->where('user_id', auth()->id());
    }

    public function savedMessages()
    {
        return $this->hasMany(SaveMessage::class);
    }
    public function isSaved()
    {
        return SaveMessage::where('message_id', $this->id)
            ->where('user_id', Auth::id())
            ->exists();
    }

    public function isDeletedForUser($userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $this->deletedMessages()->where('user_id', $userId)->exists();
    }
    public function isRead()
    {
        $group = $this->groups()->where('group_id', auth()->user()->group_id)->first();
        return $group ? $group->pivot->is_read : 0;
    }


}
