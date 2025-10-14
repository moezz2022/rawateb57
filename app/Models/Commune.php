<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;

    protected $fillable = ['code_commune', 'name', 'daira_id']; 

    public function daira()
    {
        return $this->belongsTo(Daira::class);
    }
}
