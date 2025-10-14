<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RwMigration extends Model
{
    use HasFactory;
    protected $table = 'rw_migrations';
    protected $primaryKey = 'ID_MIGRATION'; // تحديد المفتاح الأساسي
    protected $keyType = 'int';
    public $incrementing = true;
    
    protected $fillable = [
        'MONTH',
        'LOT',
        'YEAR',
        'STATUS',
        'path',
    ];    

    public $timestamps = true;

    public function papers()
{
    return $this->hasMany(RwPaper::class, 'ID_MIGRATION', 'ID_MIGRATION');
}

public function pavars()
{
    return $this->hasMany(RwPavar::class, 'ID_MIGRATION', 'ID_MIGRATION');
}
}

