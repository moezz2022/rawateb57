<?php

namespace App\Observers;

use App\Models\Message;
use Illuminate\Support\Str;

class MessageObserver
{

    public function creating(Message $message)
    {
        if (empty($message->slug)) {
            $message->slug = Str::uuid(); 
        }
    }
    
}
