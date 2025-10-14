<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'NOM',
        'PRENOM',
        'NOMA',
        'PRENOMA',
        'DATNAIS',
        'SITFAM',
        'ENF10',
        'MATRI',
        'CLECPT',
        'NUMSS',
        'CODFONC',
        'DATENT',
        'ECH',
        'AFFECT',
        'PRIMAIRE',
        'ADM',
    ];

    protected $dates = ['DATNAIS', 'DATENT'];


    public function grade()
    {
        return $this->hasOne(Grade::class, 'codtab', 'CODFONC');
    }


    public function group()
    {
        return $this->belongsTo(Group::class, 'AFFECT', 'AFFECT');
    }

    public function rwPapers()
    {
        return $this->hasMany(RwPaper::class, 'MATRI', 'MATRI');
    }

    public function primeRendements()
    {
        return $this->hasMany(PrimeRendement::class, 'MATRI', 'MATRI');
    }
    public function primeRendementFor($year, $quarter)
    {
        return $this->primeRendements()
            ->where('year', $year)
            ->where('quarter', $quarter)
            ->first();
    }

    public function monthlyAbsences()
    {
        return $this->hasMany(MonthlyAbsence::class, 'MATRI', 'MATRI');
    }

    public function primescolarites()
    {
        return $this->hasMany(PrimeScolarite::class, 'MATRI', 'MATRI');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'ADM');
    }
    public function primaireGroup()
    {
        return $this->belongsTo(Group::class, 'PRIMAIRE', 'AFFECT');
    }

}
