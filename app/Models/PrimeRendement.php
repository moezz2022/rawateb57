<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimeRendement extends Model
{
    use HasFactory;

    protected $fillable = ['MATRI', 'ADM', 'year', 'quarter', 'mark', 'absence_days', 'notes'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'MATRI', 'MATRI');
    }  
}


