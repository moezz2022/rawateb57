<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RwMigrationRndm extends Model
{
    use HasFactory;
    protected $table = 'rw_migrations_rndm';
    protected $primaryKey = 'ID_MIGRATION'; // تحديد المفتاح الأساسي
    protected $keyType = 'int';
    public $incrementing = true;
    
    protected $fillable = [
        'LOT',
        'TRIMESTER',
        'YEAR',
        'TITLE',
        'STATUS',
        'path',
    ];    

    public $timestamps = true;

    public function papers()
{
    return $this->hasMany(PaperRndm::class, 'ID_MIGRATION', 'ID_MIGRATION');
}

public function pavars()
{
    return $this->hasMany(PavarRndm::class, 'ID_MIGRATION', 'ID_MIGRATION');
}
}

