<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperRndm extends Model
{
    use HasFactory;

    protected $table = 'papers_rndm';

    protected $fillable = [
        'MATRI',
        'ID_MIGRATION',
        'CATEG',
        'ECH',
        'ADM',
        'SALBASE',
        'TOTGAIN',
        'BRUTSS',
        'RETITS',
        'RETSS',
        'NETPAI',
        'BRUTMENS',
        'TAUX',
        'JRPRIME'
    ];

    public function migration()
    {
        return $this->belongsTo(RwMigrationRndm::class, 'ID_MIGRATION', 'ID_MIGRATION');
    }
    public function rwPavars()
    {
        return $this->hasMany(PavarRndm::class, 'MATRI', 'MATRI');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'CODFONC', 'codtab');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'AFFECT', 'AFFECT')->whereNotNull('AFFECT');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'ADM', 'ADM');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'MATRI', 'MATRI');
    }
}
