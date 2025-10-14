<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use InvalidArgumentException;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'AFFECT',
        'name',
        'type',
        'parent_id',
    ];

    protected static $validTypes = ['admin', 'education', 'inspection'];

 
    public function parent()
    {
        return $this->belongsTo(Group::class, 'parent_id');
    }

    
    public function children()
    {
        return $this->hasMany(Group::class, 'parent_id');
    }

    public function setTypeAttribute($value)
    {
        if (!in_array($value, self::$validTypes)) {
            throw new InvalidArgumentException('تم تحديد نوع غير صالح.');
        }
        $this->attributes['type'] = $value;
    }

    
    public function getTypeLabelAttribute()
    {
        $types = [
            'admin' => 'مديرية',
            'education' => 'المؤسسات التربوية',
            'inspection' => 'الهيئة التفتيشية',
        ];

        return $types[$this->type] ?? 'غير محدد';
    }

   
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_group', 'group_id', 'user_id');
    }

   
    public function employees()
    {
        return $this->hasMany(Employee::class, 'AFFECT', 'AFFECT');
    }

}
