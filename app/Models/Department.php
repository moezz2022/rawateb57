<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    
    protected $table = 'departments';
    
    protected $fillable = [
        'ADM', // معرف الإدارة
        'name', // اسم الإدارة
    ];

    // علاقة قسم بالرواتب (إدارة تحتوي على عدة رواتب في rw_papers)
    public function rwPapers()
    {
        return $this->hasMany(RwPaper::class, 'ADM', 'ADM');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'ADM', 'ADM');
    }
}
