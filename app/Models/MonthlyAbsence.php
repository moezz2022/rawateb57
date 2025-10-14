<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyAbsence extends Model
{
    use HasFactory;

    protected $fillable = [
        'MATRI',
        'month',
        'year',
        'absence_days',
        'absence_reason',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'MATRI', 'MATRI');
    }  
}