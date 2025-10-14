<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimeScolarite extends Model
{
    use HasFactory;

    protected $fillable = [
        'MATRI',
        'year',
        'ENF',
        'ENFSCO',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'MATRI', 'MATRI');
    }  
}