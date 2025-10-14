<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Message;
use App\Observers\MessageObserver;
class AppServiceProvider extends ServiceProvider
{
   
    
    public function boot()
    {
        Message::observe(MessageObserver::class);
    }
    
}
