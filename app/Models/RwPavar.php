<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RwPavar extends Model
{
    use HasFactory;
    protected $table = 'rw_pavar'; 

    protected $fillable = [
        'MATRI',
        'ID_MIGRATION',
        'IND',
        'ADM',
        'MONTANT',
    ];
    public function migration()
    {
        return $this->belongsTo(RwMigration::class, 'ID_MIGRATION', 'ID_MIGRATION');
    }
    public function salaryElement()
    {
        return $this->belongsTo(SalaryElement::class, 'IND', 'IND');
    }
}
