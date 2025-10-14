<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daira extends Model
{
    use HasFactory;

    protected $fillable = ['code_daira', 'name']; 

    public function communes()
    {
        return $this->hasMany(Commune::class);
    }
}


