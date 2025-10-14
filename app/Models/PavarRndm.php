<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PavarRndm extends Model
{
    use HasFactory;
    protected $table = 'pavars_rndm';

    protected $fillable = [
        'MATRI',
        'ID_MIGRATION',
        'IND',
        'ADM',
        'MONTANT',
    ];
    public function migration()
    {
        return $this->belongsTo(RwMigrationRndm::class, 'ID_MIGRATION', 'ID_MIGRATION');
    }
    public function salaryElement()
    {
        return $this->belongsTo(SalaryElement::class, 'IND', 'IND');
    }
}
