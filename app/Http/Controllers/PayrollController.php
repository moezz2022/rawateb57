<?php

namespace App\Http\Controllers;

use App\Models\RwMigration;
use App\Models\RwMigrationRndm;
use App\Models\RwPaper;
use App\Models\Group;
use App\Models\Employee;
use App\Models\PaperRndm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\Grade;
use Illuminate\Support\Facades\View;
class PayrollController extends Controller
{

    public function show(Request $request)
    {
        $employees = collect();

        $years = RwMigration::select('YEAR')
            ->distinct()
            ->orderBy('YEAR', 'desc')
            ->pluck('YEAR')
            ->toArray();

        if (empty($years)) {
            $years = [date('Y')];
        }

        $selectedYear = (int) ($request->input('year') ?: max($years));

        $migrations = RwMigration::where('YEAR', $selectedYear)
            ->orderBy('MONTH', 'desc')
            ->get();

        $yearsrndm = RwMigrationRndm::select('YEAR')
            ->distinct()
            ->orderBy('YEAR', 'desc')
            ->pluck('YEAR')
            ->toArray();

        if (empty($yearsrndm)) {
            $yearsrndm = [date('Y')];
        }

        $selectedYearRndm = (int) ($request->input('yearrndm') ?: max($yearsrndm));

        $rndm_migrations = RwMigrationRndm::where('YEAR', $selectedYearRndm)
            ->orderBy('TRIMESTER', 'desc')
            ->get();

        return view('paie.show', compact(
            'employees',
            'migrations',
            'years',
            'selectedYear',
            'yearsrndm',
            'selectedYearRndm',
            'rndm_migrations'
        ));
    }





    public function search(Request $request)
    {
        $search = $request->input('search');
        $month = $request->input('month');
        $year = $request->input('year');
        $migration = RwMigration::where('YEAR', $year)
            ->where('MONTH', $month)
            ->first();

        $grades = Grade::all();
        $employees = Employee::where('NOMA', 'LIKE', "%{$search}%")
            ->orWhere('PRENOMA', 'LIKE', "%{$search}%")
            ->orWhere('MATRI', 'LIKE', "%{$search}%")
            ->get()
            ->unique('MATRI');

        return view('paie.results_salary', [
            'employees' => $employees,
            'grades' => $grades,
            'search' => $search,
            'month' => $month,
            'year' => $year,
            'migration' => $migration
        ]);
    }

    public function showSalarySlip($matri, $month, $year)
    {
        try {
            $lang = request('lang', 'ar'); // افتراضي: عربي

            // أسماء الأشهر
            $monthsArabic = [
                '01' => 'جانفي',
                '02' => 'فيفري',
                '03' => 'مارس',
                '04' => 'أفريل',
                '05' => 'ماي',
                '06' => 'جوان',
                '07' => 'جويلية',
                '08' => 'أوت',
                '09' => 'سبتمبر',
                '10' => 'أكتوبر',
                '11' => 'نوفمبر',
                '12' => 'ديسمبر',
            ];

            $monthsFrench = [
                '01' => 'Janvier',
                '02' => 'Février',
                '03' => 'Mars',
                '04' => 'Avril',
                '05' => 'Mai',
                '06' => 'Juin',
                '07' => 'Juillet',
                '08' => 'Août',
                '09' => 'Septembre',
                '10' => 'Octobre',
                '11' => 'Novembre',
                '12' => 'Décembre',
            ];

            // تنسيق الشهر
            $monthFormatted = str_pad($month, 2, '0', STR_PAD_LEFT);
            $monthName = $lang === 'fr'
                ? ($monthsFrench[$monthFormatted] ?? 'Inconnu')
                : ($monthsArabic[$monthFormatted] ?? 'غير معروف');

            // حساب الثلاثي حسب الشهر المطلوب
            $trimester = ceil(((int) $monthFormatted) / 3);

            // جلب ترحيل الراتب
            $migration = RwMigration::where('YEAR', $year)
                ->where('MONTH', $monthFormatted)
                ->first();

            if (!$migration) {
                $msg = $lang === 'fr'
                    ? "❌ Pas de bulletin pour $monthName $year."
                    : "❌ لا يوجد كشف الراتب لشهر $monthName $year.";
                return response()->json(['message' => $msg], 404);
            }

            $migrationId = $migration->ID_MIGRATION;

            // جلب الموظف
            $employee = Employee::where('MATRI', $matri)->first();
            if (!$employee) {
                $msg = $lang === 'fr'
                    ? "❌ Employé introuvable."
                    : "❌ الموظف غير موجود.";
                return response()->json(['message' => $msg], 404);
            }
            // جلب ورقة الراتب
            $rwPaper = RwPaper::with([
                'rwPavars' => function ($query) use ($migrationId) {
                    $query->where('ID_MIGRATION', $migrationId);
                },
                'rwPavars.salaryElement',
                'migration'
            ])
                ->where('MATRI', $matri)
                ->whereHas('migration', function ($query) use ($migrationId) {
                    $query->where('ID_MIGRATION', $migrationId);
                })
                ->first();

            if (!$rwPaper) {
                $msg = $lang === 'fr'
                    ? "❌ Aucun bulletin pour cet employé dans cette migration."
                    : "❌ لا يوجد كشف راتب لهذا الموظف في هذا الترحيل.";
                return response()->json(['message' => $msg], 404);
            }

            $salaryInds = [001, 101, 103, 105, 187, 206, 207, 208, 210, 211, 216, 225, 226, 227, 228, 229, 241, 242, 245, 246, 260, 261, 262, 270, 271, 272, 273, 280, 290, 305];
            $allocationFamilialeInds = [990, 401, 991];
            $socialServiceInds = [660, 388, 397, 399, 398, 301, 302, 303];

            $nameField = $lang === 'fr' ? 'nameFR' : 'nameAR';

            $salaryDetails = $rwPaper->rwPavars
                ->whereIn('IND', $salaryInds)
                ->map(fn($rwPavar) => [
                    'IND' => $rwPavar->IND,
                    'ElementName' => optional($rwPavar->salaryElement)->$nameField ?? ($lang === 'fr' ? 'Inexistant' : 'غير موجود'),
                    'MONTANT' => $rwPavar->MONTANT,
                ])
                ->unique('ElementName')
                ->values();

            $allocationFamiliales = $rwPaper->rwPavars
                ->whereIn('IND', $allocationFamilialeInds)
                ->map(fn($rwPavar) => [
                    'IND' => $rwPavar->IND,
                    'ElementName' => optional($rwPavar->salaryElement)->$nameField ?? ($lang === 'fr' ? 'Inexistant' : 'غير موجود'),
                    'MONTANT' => $rwPavar->MONTANT,
                ])
                ->unique('ElementName')
                ->values();

            $socialServicesDetails = $rwPaper->rwPavars
                ->whereIn('IND', $socialServiceInds)
                ->map(fn($rwPavar) => [
                    'IND' => $rwPavar->IND,
                    'ElementName' => optional($rwPavar->salaryElement)->$nameField ?? ($lang === 'fr' ? 'Inexistant' : 'غير موجود'),
                    'MONTANT' => $rwPavar->MONTANT,
                ])
                ->unique('ElementName')
                ->values();

            $rndmMigration = RwMigrationRndm::where('YEAR', $year)
                ->where('TRIMESTER', $trimester)
                ->first();

            $mrdiyya = $BRUTMENS = $RETSS = $RETITS = $TOTGAIN = $BRUTSS = null;

            if ($rndmMigration) {
                $rwPaperRndm = PaperRndm::where('MATRI', $matri)
                    ->where('ID_MIGRATION', $rndmMigration->ID_MIGRATION)
                    ->first();

                if ($rwPaperRndm) {
                    $mrdiyya = $rwPaperRndm->NETPAI;
                    $BRUTMENS = $rwPaperRndm->BRUTMENS;
                    $RETSS = $rwPaperRndm->RETSS;
                    $RETITS = $rwPaperRndm->RETITS;
                    $TOTGAIN = $rwPaperRndm->TOTGAIN;
                    $BRUTSS = $rwPaperRndm->BRUTSS;
                }
            }

            $viewName = $lang === 'fr' ? 'paie.salarySlipFr' : 'paie.salarySlip';

            return view($viewName, compact(
                'employee',
                'rwPaper',
                'salaryDetails',
                'allocationFamiliales',
                'socialServicesDetails',
                'mrdiyya',
                'BRUTMENS',
                'RETSS',
                'RETITS',
                'TOTGAIN',
                'BRUTSS',
                'monthName',
                'year',
                'lang',
            ));

        } catch (\Exception $e) {
            Log::error('❌ خطأ أثناء تحميل كشف الراتب: ' . $e->getMessage());
            $msg = request('lang', 'ar') === 'fr'
                ? '❌ Erreur lors du chargement du bulletin.'
                : '❌ حدث خطأ أثناء تحميل كشف الراتب.';
            return response()->json(['message' => $msg], 500);
        }
    }





    public function pay_show()
    {
        $employees = collect();
        return view('paie.salaryannualshow', compact('employees'));
    }

    public function searchannual(Request $request)
    {
        $search = $request->input('search');

        $employees = Employee::where('NOMA', 'LIKE', "%{$search}%")
            ->orWhere('PRENOMA', 'LIKE', "%{$search}%")
            ->orWhere('MATRI', 'LIKE', "%{$search}%")
            ->distinct('MATRI')
            ->get();

        $response = '';
        if ($employees->count() > 0) {
            $seen = [];
            foreach ($employees as $index => $employee) {
                if (!in_array($employee->MATRI, $seen)) {
                    $seen[] = $employee->MATRI;

                    $response .= "<tr>
                    <td>" . ($index + 1) . "</td>
                    <td>" . $employee->NOMA . ' ' . $employee->PRENOMA . "</td>
                    <td>" . $employee->MATRI . "</td>
                    <td><a href='#' class='payroll-annual' data-nccpf='" . $employee->MATRI . "'>كشف الراتب</a></td>
                </tr>";
                }
            }
        } else {
            $response = "<tr><td colspan='4'>لا توجد نتائج للبحث.</td></tr>";
        }

        return $response;
    }
    public function showSalaryannual($matri, $year)
    {
        try {
            // 🔹 التأكد من وجود بيانات الترحيل
            $migration = RwMigration::where('YEAR', $year)
                ->first();


            if (!$migration) {
                return response()->json(['message' => "❌ لا يوجد كشف السنوي لسنة   $year."], 404);
            }

            $migrationId = $migration->ID_MIGRATION; // ✅ استخدم المعرف الصحيح

            // 🔹 البحث عن الموظف
            $employee = Employee::where('MATRI', $matri)->first();
            if (!$employee) {
                return response()->json(['message' => "❌ الموظف غير موجود."], 404);
            }

            // 🔹 البحث عن كشف الراتب بناءً على `ID_MIGRATION`
            $rwPaper = RwPaper::with([
                'rwPavars' => function ($query) use ($migrationId) {
                    $query->where('ID_MIGRATION', $migrationId);
                },
                'rwPavars.salaryElement',
                'migration'
            ])
                ->where('MATRI', $matri)
                ->whereHas('migration', function ($query) use ($migrationId) {
                    $query->where('ID_MIGRATION', $migrationId);
                })
                ->first();

            if (!$rwPaper) {
                return response()->json(['message' => "❌ لا يوجد كشف راتب لهذا الموظف في هذا الترحيل."], 404);
            }

            // **🔹 تصفية البيانات بناءً على الترحيل المحدد فقط**
            $salaryInds = [001, 101, 103, 105, 187, 206, 207, 208, 210, 211, 216, 225, 226, 227, 228, 229, 241, 242, 245, 246, 260, 261, 262, 270, 271, 272, 273, 280, 290, 305, 990, 401, 991];
            $socialServiceInds = [660, 388, 397, 399, 398, 301, 302, 303];

            // 🔹 **جلب الرواتب فقط للترحيل الحالي**
            $salaryDetails = $rwPaper->rwPavars
                ->whereIn('IND', $salaryInds)
                ->where('ID_MIGRATION', $migrationId) // ✅ فلترة حسب الترحيل
                ->map(fn($rwPavar) => [
                    'IND' => $rwPavar->IND,
                    'ElementName' => optional($rwPavar->salaryElement)->nameAR ?? 'غير موجود',
                    'MONTANT' => $rwPavar->MONTANT,
                ])
                ->unique('ElementName')
                ->values();

            // 🔹 **جلب الخدمات الاجتماعية فقط للترحيل الحالي**
            $socialServicesDetails = $rwPaper->rwPavars
                ->whereIn('IND', $socialServiceInds)
                ->where('ID_MIGRATION', $migrationId) // ✅ فلترة حسب الترحيل
                ->map(fn($rwPavar) => [
                    'IND' => $rwPavar->IND,
                    'MONTANT' => $rwPavar->MONTANT,
                    'ElementName' => optional($rwPavar->salaryElement)->nameAR ?? 'غير موجود',
                ])
                ->unique('ElementName')
                ->values();



            return view('paie.salaryannual', compact(
                'employee',
                'rwPaper',
                'salaryDetails',
                'socialServicesDetails',
                'year'
            ));

        } catch (\Exception $e) {
            Log::error('❌ خطأ أثناء تحميل كشف السنوي: ' . $e->getMessage());
            return response()->json(['message' => '❌ حدث خطأ أثناء تحميل كشف السنوي.'], 500);
        }
    }


    public function showdetails(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً.');
        }

        $user = auth()->user();
        $isAdmin = $user->role === 'admin';

        if (!$isAdmin) {
            $group = Group::whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })->first();

            $affect = optional($group)->AFFECT;
            if (!$affect) {
                return redirect()->back()->with('error', 'لم يتم العثور على AFFECT لمجموعتك.');
            }
        }

        $years = RwMigration::where('status', 1)
            ->where('YEAR', '>=', 2020)
            ->distinct()
            ->orderByDesc('YEAR')
            ->pluck('YEAR');

        // استعلام الموظفين
        $employeesQuery = Employee::orderBy('ADM', 'asc')
            ->orderBy('CODFONC', 'asc');

        // استعلام الإدارات
        $departmentsQuery = DB::table('departments')
            ->select('departments.ADM', 'departments.name', DB::raw('COUNT(employees.MATRI) as employee_count'))
            ->join('employees', 'departments.ADM', '=', 'employees.ADM')
            ->groupBy('departments.ADM', 'departments.name')
            ->orderBy('departments.name');

        // فلترة إذا لم يكن Admin
        if (!$isAdmin) {
            $employeesQuery->where('AFFECT', $affect);
            $departmentsQuery->where('employees.AFFECT', $affect);
        }

        $employees = $employeesQuery->get();
        $departments = $departmentsQuery->get();

        if ($employees->isEmpty()) {
            return redirect()->back()->with('warning', 'لا يوجد موظفون في هذه المجموعة.');
        }

        $currentAdm = $request->adm;
        if (!$currentAdm || !$departments->contains('ADM', $currentAdm)) {
            $currentAdm = $departments->first()->ADM ?? null;
        }

        return view('paie.salary_details', compact('employees', 'departments', 'currentAdm', 'years'))
            ->with('firstAdm', $departments->first());
    }




    public function salaryDetails($month, $year, $adm)
    {
        try {
            $user = auth()->user();
            $isAdmin = $user->role === 'admin';

            // تأكد أن الشهر رقم صحيح
            $month = (int) $month;

            $monthsArabic = [
                1 => 'جانفي',
                2 => 'فيفري',
                3 => 'مارس',
                4 => 'أفريل',
                5 => 'ماي',
                6 => 'جوان',
                7 => 'جويلية',
                8 => 'أوت',
                9 => 'سبتمبر',
                10 => 'أكتوبر',
                11 => 'نوفمبر',
                12 => 'ديسمبر'
            ];

            $monthName = $monthsArabic[$month] ?? 'غير معروف';

            // إذا ليس Admin، حدد AFFECT الخاص بالمجموعة
            if (!$isAdmin) {
                $affect = Group::whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })->pluck('AFFECT')->first();

                if (!$affect) {
                    return response()->json(['error' => 'لا يمكن تحديد المؤسسة الخاصة بالمستخدم.'], 403);
                }
            }

            // جلب موظفين المؤسسة أو جميع الموظفين حسب الدور
            $employeesQuery = RwPaper::with([
                'rwPavars' => function ($query) use ($month, $year) {
                    $query->whereHas('migration', fn($q) => $q->where('MONTH', $month)->where('YEAR', $year));
                },
                'rwPavars.salaryElement',
                'employee',
            ])->where('ADM', $adm)
                ->whereHas('migration', fn($query) => $query->where('MONTH', $month)->where('YEAR', $year));

            if (!$isAdmin) {
                $employeesQuery->whereHas('employee', fn($query) => $query->where('AFFECT', $affect));
            }

            $employees = $employeesQuery
                ->select(['MATRI', 'CATEG', 'TOTGAIN', 'NBRTRAV', 'NETPAI'])
                ->get();

            if ($employees->isEmpty()) {
                return response()->json(['message' => 'لا توجد بيانات رواتب متاحة لهذه الإدارة.'], 404);
            }

            $bonusIndices = [
                1,
                101,
                103,
                187,
                305,
                208,
                216,
                225,
                226,
                227,
                228,
                229,
                241,
                242,
                246,
                260,
                261,
                262,
                270,
                271,
                273,
                280,
                290,
                211,
                990,
                401,
                991,
                660,
                610,
                980,
                397,
                399,
                398,
                301,
                302,
                303
            ];

            $salaryData = $employees->map(function ($employee) use ($bonusIndices, $month, $year) {
                $rwPavars = $employee->rwPavars->filter(function ($rwPavar) use ($month, $year) {
                    return $rwPavar->migration &&
                        (int) $rwPavar->migration->MONTH === (int) $month &&
                        (int) $rwPavar->migration->YEAR === (int) $year;
                });

                $groupedDetails = $rwPavars->map(function ($rwPavar) {
                    return [
                        'IND' => $rwPavar->IND,
                        'ElementName' => $rwPavar->salaryElement->nameAR ?? 'غير موجود',
                        'MONTANT' => isset($rwPavar->MONTANT) ? number_format($rwPavar->MONTANT, 2) : '0.00'
                    ];
                })->groupBy(fn($item) => in_array($item['IND'], $bonusIndices) ? 'BonusDetails' : 'SalaryDetails');

                return [
                    'MATRI' => $employee->MATRI,
                    'CATEG' => $employee->CATEG,
                    'ECH' => $employee->employee->ECH ?? 'غير متوفر',
                    'SITFAM' => $employee->employee->SITFAM ?? 'غير متوفر',
                    'ENF10' => $employee->employee->ENF10 ?? 'غير متوفر',
                    'TOTGAIN' => $employee->TOTGAIN,
                    'NBRTRAV' => isset($employee->NBRTRAV) ? number_format($employee->NBRTRAV, 0) : '0',
                    'Name' => trim("{$employee->employee->NOMA} {$employee->employee->PRENOMA}") ?? 'غير معروف',
                    'TotalSalary' => isset($employee->NETPAI) ? number_format($employee->NETPAI, 2) : '0.00',
                    'SalaryDetails' => $groupedDetails->get('SalaryDetails', collect())->unique('IND')->values(),
                    'BonusDetails' => $groupedDetails->get('BonusDetails', collect())->unique('IND')->values(),
                ];
            })->unique('MATRI')->values();

            return response()->json([
                'salaryData' => $salaryData,
                'monthName' => $monthName,
                'year' => $year
            ]);

        } catch (\Exception $e) {
            \Log::error('خطأ أثناء جلب بيانات الرواتب: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء تحميل بيانات الرواتب.'], 500);
        }
    }




    public function show_report()
    {
        $employees = collect();
        return view('paie.salaryreport', compact('employees'));
    }

    public function showDetailedSalaryRange($matri, $year, $startMonth, $endMonth)
    {
        try {
            $user = auth()->user();

            if ((int) $startMonth > (int) $endMonth) {
                return response()->json(['error' => 'الشهر الأول يجب أن يكون قبل أو يساوي الشهر الأخير.'], 422);
            }

            $monthsArabic = [
                '01' => 'جانفي',
                '02' => 'فيفري',
                '03' => 'مارس',
                '04' => 'أفريل',
                '05' => 'ماي',
                '06' => 'جوان',
                '07' => 'جويلية',
                '08' => 'أوت',
                '09' => 'سبتمبر',
                '10' => 'أكتوبر',
                '11' => 'نوفمبر',
                '12' => 'ديسمبر'
            ];

            $bonusIndices = [1, 101, 103, 187, 305, 208, 216, 225, 226, 227, 228, 229, 241, 242, 246, 260, 261, 262, 270, 271, 273, 280, 290, 211, 990, 401, 991, 660, 610, 980, 397, 399, 398, 301, 302, 303];

            $results = [];
            $missingMonths = [];

            for ($month = (int) $startMonth; $month <= (int) $endMonth; $month++) {
                $monthStr = str_pad($month, 2, '0', STR_PAD_LEFT);

                $rw = RwPaper::with(['rwPavars.migration', 'rwPavars.salaryElement', 'migration', 'employee'])
                    ->where('MATRI', $matri)
                    ->whereHas('migration', fn($query) => $query->where('MONTH', $month)->where('YEAR', $year))
                    ->first();

                if (!$rw) {
                    $missingMonths[] = $monthsArabic[$monthStr];
                    continue;
                }

                // ✅ تصفية عناصر الراتب حسب الشهر والسنة
                $filteredRwPavars = $rw->rwPavars->filter(function ($rwPavar) use ($month, $year) {
                    return $rwPavar->migration &&
                        (int) $rwPavar->migration->MONTH === (int) $month &&
                        (int) $rwPavar->migration->YEAR === (int) $year;
                });

                $groupedDetails = $filteredRwPavars->map(function ($item) {
                    return [
                        'IND' => $item->IND,
                        'ElementName' => $item->salaryElement->nameAR ?? 'غير موجود',
                        'MONTANT' => round((float) $item->MONTANT, 2)
                    ];
                })->groupBy(function ($item) use ($bonusIndices) {
                    return in_array($item['IND'], $bonusIndices) ? 'BonusDetails' : 'SalaryDetails';
                });


                $results[] = [
                    'month_number' => (int) $month,
                    'month_string' => $monthStr,
                    'month_arabic' => $monthsArabic[$monthStr],
                    'TOTGAIN' => round((float) $rw->TOTGAIN, 2),
                    'NETPAI' => round((float) $rw->NETPAI, 2),
                    'NBRTRAV' => round((float) ($rw->NBRTRAV ?? 0), 2),
                    'SalaryDetails' => $groupedDetails->get('SalaryDetails', collect())->unique('IND')->values(),
                    'BonusDetails' => $groupedDetails->get('BonusDetails', collect())->unique('IND')->values(),
                    'CATEG' => $rw->CATEG,
                    'ECH' => $rw->ECH,
                    'SITFAM' => $rw->employee->SITFAM ?? 'غير متوفر',
                    'ENF10' => $rw->employee->ENF10 ?? 'غير متوفر',
                    'Name' => trim("{$rw->employee->NOMA} {$rw->employee->PRENOMA}"),
                    'Rank' => $rw->employee?->grade?->name ?? 'غير متوفر',

                ];
            }

            if (empty($results)) {
                return response()->json([
                    'message' => 'لا توجد بيانات خلال الفترة المحددة.',
                    'missing_months' => $missingMonths
                ], 404);
            }

            return response()->json([
                'employee' => [
                    'matricule' => $matri,
                    'full_name' => $results[0]['Name'],
                    'rank' => $results[0]['Rank'],
                    'CATEG' => $results[0]['CATEG'],
                    'ECH' => $results[0]['ECH'],
                    'SITFAM' => $results[0]['SITFAM'],
                    'ENF10' => $results[0]['ENF10'],
                ],
                'year' => $year,
                'salaries' => $results,
                'missing_months' => $missingMonths
            ]);

        } catch (\Exception $e) {
            \Log::error('خطأ أثناء تحميل تقرير الرواتب للموظف: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء تحميل البيانات.'], 500);
        }
    }



}