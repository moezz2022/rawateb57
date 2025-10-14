<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'concours_id',
        'type',
        'path',
        'status', // ✅ أضف هذا الحقل
    ];

    protected $casts = [
        'status' => 'string', // ✅ تأكد أنه يتعامل كنص (يمنع الخطأ SQL 1265)
    ];

    /**
     * العلاقة مع المترشح (Concours)
     */
    public function concours()
    {
        return $this->belongsTo(Concours::class, 'concours_id');
    }

    /**
     * للتحقق من مطابقة الوثيقة
     */
    public function isMatching(): bool
    {
        return $this->status === 'مطابق';
    }

    /**
     * للتحقق من عدم المطابقة
     */
    public function isNotMatching(): bool
    {
        return $this->status === 'غير مطابق';
    }
}
