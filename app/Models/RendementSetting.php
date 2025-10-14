<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendementSetting extends Model
{
    protected $fillable = ['year', 'quarter', 'period', 'is_open'];

    public function getPeriodAttribute()
    {
        return match ($this->quarter) {
            1 => 'الأول',
            2 => 'الثاني',
            3 => 'الثالث',
            4 => 'الرابع',
            default => null,
        };
    }

    
    public function getMonthsForPeriod()
    {
        return match ($this->quarter) {
            1 => [1, 2, 3],
            2 => [4, 5, 6],
            3 => [7, 8, 9],
            4 => [10, 11, 12],
            default => [],
        };
    }
}



