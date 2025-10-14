<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Concours extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'password',
        'daira_id',
        'commune_id',
        'con_grade',
        'diploma',
        'specialty',
        'NomArF',
        'PrenomArF',
        'gender',
        'DateNaiF',
        'LieuNaiArF',
        'birthNum',
        'familyStatus',
        'childrenNumber',
        'residenceMunicipality',
        'personalAddress',
        'phoneNumber',
        'serviceState',
        'serviceNum',
        'servIsDate',
        'status',

    ];


    public function documents()
    {
        return $this->hasMany(Document::class);
    }

   public function updateStatusBasedOnDocuments()
{
    $documents = $this->documents;

    if ($documents->isEmpty()) {
        $newStatus = 'قيد الدراسة';
    } elseif ($documents->every(fn($doc) => $doc->status === 'مطابق')) {
        $newStatus = 'مطابق';
    } elseif ($documents->contains(fn($doc) => $doc->status === 'غير مطابق')) {
        $newStatus = 'غير مطابق';
    } else {
        $newStatus = 'قيد الدراسة';
    }

    // تحديث فقط إذا تغيّرت الحالة فعلاً (تحسين بسيط لتقليل عدد عمليات الحفظ)
    if ($this->status !== $newStatus) {
        $this->status = $newStatus;
        $this->save();
    }

    return $this->status;
}



    public function daira()
    {
        return $this->belongsTo(Daira::class);
    }

    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }

    public function getGenderTextAttribute()
    {
        return $this->gender == 1 ? 'ذكر' : 'أنثى';
    }

    public function getFamilyStatus($familyStatus)
    {
        $statuses = [
            1 => 'متزوج (ة)',
            2 => 'أعزب (عزباء)',
            3 => 'مطلق (ة)',
            4 => 'أرمل (ة)',
        ];
        return $statuses[$familyStatus] ?? ' ';
    }

    public function getResidenceMunicipality($residenceMunicipality)
    {
        $residence = [
            57271 => 'المغير',
            57272 => 'سيدي خليل',
            57273 => 'أم الطيور',
            57274 => 'سطيل',
            57281 => 'جامعة',
            57282 => 'المرارة',
            57283 => 'تندلة',
            57284 => 'سيدي عمران',
        ];
        return $residence[$residenceMunicipality] ?? ' ';
    }
    public static function getGradeLabel($grade)
    {
        $grades = [
            1 => 'عامل مهني من المستوى الأول',
            2 => 'عامل مهني من المستوى الثاني',
            3 => 'عامل مهني من المستوى الثالث',
            4 => 'عون خدمة من المستوى الثالث',
            5 => 'سائق سيارة من المستوى الأول',
        ];

        return $grades[$grade] ?? ' ';
    }
    public static function getDiploma($diploma)
    {
        $diplomas = [
            1 => 'شهادة التكوين المهني المتخصص',
            2 => 'شهادة الكفاءة المهنية',
        ];
        return $diplomas[$diploma] ?? ' ';
    }
    public static function getSpecialty($specialty)
    {
        $specialtys = [
            1 => 'طبخ الجماعات ',
            2 => 'نجارة الألمنيوم',
            3 => 'تركيب صحي وغاز',
            4 => 'تركيب وصيانة أجهزة التبريد والتكييف',
            5 => 'الكهرباء المعمارية',
            6 => 'تلحيم',
            7 => 'بستنة',
        ];
        return $specialtys[$specialty] ?? ' ';
    }


}
