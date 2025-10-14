<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\RwMigration;
use App\Models\RwPaper;
use Illuminate\Http\Request;
use App\Models\RwMigrationRndm;
use App\Models\PaperRndm;

class AtsController extends Controller
{
    /**
     * عرض صفحة الإعدادات مع السنوات المتاحة
     */
    public function index()
    {
        $years = RwMigration::select('YEAR')
            ->distinct()
            ->orderBy('YEAR', 'desc')
            ->pluck('YEAR');

        return view('ats.settings', compact('years'));
    }

    /**
     * ✅ جلب الأشهر حسب السنة
     */
    public function getMonths($year)
    {
        // التحقق أن السنة رقم صحيح
        if (!ctype_digit((string) $year)) {
            return response()->json([], 400);
        }

        $months = RwMigration::where('YEAR', $year)
            ->select('MONTH')
            ->distinct()
            ->orderBy('MONTH')
            ->pluck('MONTH');

        return response()->json($months);
    }

    /**
     * ✅ جلب الموظفين حسب (سنة + شهر)
     */
    public function getEmployees($year, $month)
    {
        if (!ctype_digit((string) $year) || !ctype_digit((string) $month)) {
            return response()->json([], 400);
        }

        $employees = Employee::whereHas('rwPapers.migration', function ($q) use ($year, $month) {
            $q->where('YEAR', $year)->where('MONTH', $month);
        })
            ->select('MATRI', 'NOMA', 'PRENOMA')
            ->orderBy('NOMA')
            ->get();

        return response()->json($employees);
    }


    public function getSalaries($matricule, $year, $month, $duration)
    {
        // التحقق من المدخلات
        if (!ctype_digit((string) $year) || !ctype_digit((string) $month) || !ctype_digit((string) $duration)) {
            return response()->json([], 400);
        }

        $year = (int) $year;
        $month = (int) $month;
        $duration = (int) $duration;

        // حساب بداية المدة
        $startDate = \Carbon\Carbon::create($year, $month, 1)->subMonths($duration - 1);
        $endDate = \Carbon\Carbon::create($year, $month, 1);

        // استرجاع الرواتب
        $salaries = RwPaper::where('MATRI', $matricule)
            ->whereHas('migration', function ($q) use ($startDate, $endDate) {
                $q->whereRaw("STR_TO_DATE(CONCAT(MONTH,'/',YEAR), '%m/%Y') BETWEEN ? AND ?", [
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d')
                ]);
            })
            ->with('migration')
            ->orderBy('migration.YEAR')
            ->orderBy('migration.MONTH')
            ->get();

        return response()->json($salaries);
    }

    /**
     * ✅ توليد شهادة العمل والأجر
     */
    public function ats1(Request $request, $matricule)
    {
        // جلب بيانات الموظف
        $employee = Employee::where('MATRI', $matricule)->firstOrFail();

        // عدد الأشهر (مدة) - افتراضي 12 إذا ما أرسل
        $duration = $request->input('duration', 12);

        // إرجاع صفحة HTML (Blade)
        return view('ats.atspage1', [
            'employee' => $employee,
            'year' => $request->input('year'),
            'month' => $request->input('month'),
            'duration' => $duration,
        ]);

    }

public function ats2(Request $request, $matricule)
{
    $employee = Employee::where('MATRI', $matricule)->firstOrFail();

    // استخدام الجلسة أو القيم الافتراضية
    $duration = (int) $request->input('duration', session('duration', 12));
    $year     = (int) $request->input('year', session('year', now()->year));
    $month    = (int) $request->input('month', session('month', now()->month));

    // حفظ القيم في الجلسة لتبقى عند الرجوع
    session(['duration' => $duration, 'year' => $year, 'month' => $month]);

    // نجيب أشهر الرواتب
    $salaries = RwPaper::where('rw_papers.MATRI', $matricule)
        ->join('rw_migrations', 'rw_papers.ID_MIGRATION', '=', 'rw_migrations.ID_MIGRATION')
        ->select(
            'rw_papers.*',
            'rw_migrations.YEAR',
            'rw_migrations.MONTH',
            'rw_migrations.ID_MIGRATION'
        )
        ->whereRaw('(rw_migrations.YEAR * 100 + rw_migrations.MONTH) <= ?', [$year * 100 + $month])
        ->orderByRaw('rw_migrations.YEAR * 100 + rw_migrations.MONTH DESC')
        ->get()
        ->unique(fn($item) => $item->YEAR . '-' . $item->MONTH)
        ->take($duration);

    // 🔹 إضافة المردودية لكل شهر كما في السابق
    foreach ($salaries as $salary) {
        $trimester = ceil(((int) $salary->MONTH) / 3);

        $rndmMigration = RwMigrationRndm::where('YEAR', $salary->YEAR)
            ->where('TRIMESTER', $trimester)
            ->first();

        $salary->MRDODIYA = null;
        $salary->BRUTSS_RNDM = null;
        $salary->RETSS_RNDM = null;
        $salary->BRUTMENS_RNDM = null;
        $salary->RETITS_RNDM = null;
        $salary->TOTGAIN_RNDM = null;

        if ($rndmMigration) {
            $rwPaperRndm = PaperRndm::where('MATRI', $matricule)
                ->where('ID_MIGRATION', $rndmMigration->ID_MIGRATION)
                ->first();

            if ($rwPaperRndm) {
                $salary->MRDODIYA = $rwPaperRndm->NETPAI;
                $salary->BRUTSS_RNDM = $rwPaperRndm->BRUTSS;
                $salary->RETSS_RNDM = $rwPaperRndm->RETSS;
                $salary->BRUTMENS_RNDM = $rwPaperRndm->BRUTMENS;
                $salary->RETITS_RNDM = $rwPaperRndm->RETITS;
                $salary->TOTGAIN_RNDM = $rwPaperRndm->TOTGAIN;
            }
        }
    }

    return view('ats.atspage2', [
        'employee' => $employee,
        'salaries' => $salaries,
        'year'     => $year,
        'month'    => $month,
        'duration' => $duration,
    ]);
}


    public function ats3(Request $request, $matricule)
    {
        // جلب بيانات الموظف
        $employee = Employee::where('MATRI', $matricule)->firstOrFail();

        // عدد الأشهر (مدة) - افتراضي 12 إذا ما أرسل
        $duration = $request->input('duration', 12);

        // إرجاع صفحة HTML (Blade)
        return view('ats.atspage3', [
            'employee' => $employee,
            'year' => $request->input('year'),
            'month' => $request->input('month'),
            'duration' => $duration,
        ]);

    }






}
