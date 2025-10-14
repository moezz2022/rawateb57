<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalaryElementSeeder extends Seeder
{
    public function run()
    {
        DB::table('salary_elements')->insert([
            ['IND' => '001', 'nameAR' => 'الأجر القاعدي', 'nameFR' => 'Salaire de base'],
            ['IND' => '101', 'nameAR' => 'منحة الخبرة المهنية', 'nameFR' => 'I.e.professionnelle'],
            ['IND' => '103', 'nameAR' => 'المنحة البيداغوجية', 'nameFR' => 'I.e.pédagogique'],
            ['IND' => '305', 'nameAR' => 'منحة المنصب العالي', 'nameFR' => 'Indemnité de poste élevé'],
            ['IND' => '187', 'nameAR' => 'منحة فارق الدخل', 'nameFR' => 'Indemnité de différence de revenu'],
            ['IND' => '206', 'nameAR' => 'منحة الضرر', 'nameFR' => 'Indemnité de nuisance'],
            ['IND' => '208', 'nameAR' => 'المنحة الجزافية', 'nameFR' => 'Indemnité forfaitaire complémentaire'],
            ['IND' => '210', 'nameAR' => 'منحة الضرر', 'nameFR' => 'Indemnité de nuisance'],
            ['IND' => '211', 'nameAR' => 'منحة السكن', 'nameFR' => 'Indemnité de logement'],
            ['IND' => '216', 'nameAR' => 'منحة فارق درجتين', 'nameFR' => 'Indemnité de différence de grades'],
            ['IND' => '225', 'nameAR' => 'منحة المنطقة', 'nameFR' => 'Indemnité de zone'],
            ['IND' => '226', 'nameAR' => 'منحة المنطقة', 'nameFR' => 'Indemnité de zone'],
            ['IND' => '227', 'nameAR' => 'منحة التمثيل', 'nameFR' => 'Indemnité de représentation'],
            ['IND' => '228', 'nameAR' => 'منحة نوعية للإلزام', 'nameFR' => 'Indemnité de qualité obligatoire'],
            ['IND' => '241', 'nameAR' => 'منحة التسيير المالي والمادي', 'nameFR' => 'Indemnité de gestion financière et matérielle'],
            ['IND' => '242', 'nameAR' => 'منحة تسيير المؤسسة', 'nameFR' => 'Indemnité de gestion détablissement'],
            ['IND' => '246', 'nameAR' => 'منحة التأهيل', 'nameFR' => 'Indemnité de qualification'],
            ['IND' => '260', 'nameAR' => 'منحة الجنوب', 'nameFR' => 'Indemnité du sud'],
            ['IND' => '261', 'nameAR' => 'منحة المنصب', 'nameFR' => 'Indemnité de poste'],
            ['IND' => '262', 'nameAR' => 'منحة السيارة', 'nameFR' => 'Indemnité de voiture'],
            ['IND' => '270', 'nameAR' => 'منحة الخدمات الادارية المشتركة', 'nameFR' => 'Indemnité des services administratifs communs'],
            ['IND' => '271', 'nameAR' => 'منحة تعويض الخدمات التقنية', 'nameFR' => 'Indemnité de compensation des services techniques'],
            ['IND' => '273', 'nameAR' => 'منحة دعم النشاط الإداري', 'nameFR' => 'Indemnité de soutien aux activités administratives'],
            ['IND' => '280', 'nameAR' => 'منحة دعم مدرسي بيداغوجي', 'nameFR' => 'Ind de soutien scolaire et rndm pédag'],
            ['IND' => '290', 'nameAR' => 'منحة التوثيق', 'nameFR' => 'Indemnité de documentation'],
            ['IND' => '301', 'nameAR' => 'اقتطاع الغياب', 'nameFR' => 'Prélèvement pour absence'],
            ['IND' => '302', 'nameAR' => 'اقتطاع الإضراب', 'nameFR' => 'Prélèvement pour grève'],
            ['IND' => '303', 'nameAR' => 'اقتطاع المعارضة', 'nameFR' => 'Prélèvement pour opposition'],
            ['IND' => '388', 'nameAR' => 'اقتطاع التقاعد النسبي', 'nameFR' => 'Prélèvement pour retraite proportionnelle'],
            ['IND' => '397', 'nameAR' => 'اقتطاع الاستهلاكية 01', 'nameFR' => 'Prélèvement des services sociaux 1'],
            ['IND' => '398', 'nameAR' => 'اقتطاع الاستهلاكية 02', 'nameFR' => 'Prélèvement des services sociaux 2'],
            ['IND' => '399', 'nameAR' => 'اقتطاع الخدمات', 'nameFR' => 'Prélèvement des services'],
            ['IND' => '401', 'nameAR' => 'الأجر الوحيد / الدخل الوحيد', 'nameFR' => 'Indemnité de Salaire Unique'],
            ['IND' => '610', 'nameAR' => 'اقتطاع الضمان الاجتماعي', 'nameFR' => 'Prélèvement de la sécurité sociale'],
            ['IND' => '660', 'nameAR' => 'اقتطاع التعاضدية', 'nameFR' => 'Prélèvement de la mutuelle'],
            ['IND' => '980', 'nameAR' => 'اقتطاع الضريبة على الدخل', 'nameFR' => 'Prélèvement de l\'impôt sur le revenu'],
            ['IND' => '990', 'nameAR' => 'منحة الأولاد / المنح العائلية', 'nameFR' => 'Allocation Familiale'],
            ['IND' => '991', 'nameAR' => 'منحة الأولاد أكبر من 10 سنوات', 'nameFR' => 'Majoration Allocation Familiale (+10 ans)'],
            ['IND' => '23', 'nameAR' => 'الأجر الخام', 'nameFR' => 'Salaire brut'],
            ['IND' => '999', 'nameAR' => 'الأجر الصافي', 'nameFR' => 'Salaire net'],
        ]);
    }
}

