<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyAbsenceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_open',
        'month',
        'year',
    ];
    
}