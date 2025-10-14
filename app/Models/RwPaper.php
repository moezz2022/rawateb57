<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RwPaper extends Model
{
    use HasFactory;

    protected $table = 'rw_papers';

    protected $fillable = [
        'MATRI',
        'ID_MIGRATION',
        'CATEG',
        'ECH',
        'ADM',
        'TOTGAIN',
        'BRUTSS',
        'NBRTRAV',
        'RETITS',
        'RETSS',
        'NETPAI',
    ];

    public function migration()
    {
        return $this->belongsTo(RwMigration::class, 'ID_MIGRATION', 'ID_MIGRATION');
    }
    public function rwPavars()
    {
        return $this->hasMany(RwPavar::class, 'MATRI', 'MATRI');
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
