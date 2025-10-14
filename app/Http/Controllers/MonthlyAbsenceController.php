<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\MonthlyAbsence;
use App\Models\MonthlyAbsenceSetting;
use App\Models\Group;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MonthlyAbsencesExport;

class MonthlyAbsenceController extends Controller
{
    /**
     * عرض إعدادات الأشهر (جدول الأشهر المضافة مع فتح/غلق).
     */
    public function months(Request $request)
    {
        $year = $request->get('year', date('Y'));

        $months = $this->arabicMonths();

        $monthSettings = MonthlyAbsenceSetting::where('year', $year)
            ->orderBy('month', 'desc')
            ->get();

        return view('monthly_absences.months', compact('year', 'months', 'monthSettings'));
    }


    public function settings(Request $request)
    {
        $year = $request->get('year', date('Y'));

        $months = $this->arabicMonths();

        $monthSettings = MonthlyAbsenceSetting::where('year', $year)
            ->orderBy('month', 'desc')
            ->get();

        return view('monthly_absences.settings', compact('year', 'months', 'monthSettings'));
    }
    /**
     * إضافة شهر جديد.
     */
    public function storeSetting(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2000',
            'month' => 'required|integer|between:1,12',
        ]);

        try {
            $setting = MonthlyAbsenceSetting::firstOrCreate(
                ['year' => (int) $validated['year'], 'month' => (int) $validated['month']],
                ['is_open' => false]
            );

            if (!$setting->wasRecentlyCreated) {
                return back()->with('error', 'هذا الشهر مضاف بالفعل.');
            }

            return redirect()
                ->route('monthly_absences.months', ['year' => $validated['year']])
                ->with('success', 'تمت إضافة الشهر بنجاح.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'تعذر إضافة الشهر. يرجى المحاولة لاحقًا.');
        }
    }

    /**
     * فتح/غلق الشهر.
     */
    public function toggle($id)
    {
        $setting = MonthlyAbsenceSetting::findOrFail($id);
        $setting->is_open = !$setting->is_open;
        $setting->save();

        return back()->with('success', 'تم تحديث حالة الشهر.');
    }

    /**
     * عرض صفحة الحجز للموظفين.
     */
    public function index(Request $request)
    {
        $settings = MonthlyAbsenceSetting::where('is_open', true)->get();

        if ($settings->isEmpty()) {
            return view('monthly_absences.index', [
                'employees' => collect(),
                'departments' => Department::withCount('employees')->get(),
                'settings' => $settings,
                'currentSetting' => null,
                'currentAdm' => '',
            ]);
        }

        $month = $request->get('month', $settings->first()->month);
        $year = $settings->first()->year;

        $currentSetting = $settings->firstWhere('month', $month);

        $departments = Department::withCount('employees')->get();

        $currentAdm = $request->query('adm', '');

        $employeesQuery = Employee::with([
            'monthlyAbsences' => function ($query) use ($month, $year) {
                $query->where('month', $month)->where('year', $year);
            }
        ]);

        if ($currentAdm !== '') {
            $employeesQuery->where('ADM', $currentAdm);
        }

        $employees = $employeesQuery->get();

        return view('monthly_absences.index', compact(
            'employees',
            'departments',
            'settings',
            'currentSetting',
            'currentAdm'
        ));
    }


    public function create(Request $request)
    {
        $user = auth()->user();

        $group = Group::whereHas('users', fn($q) => $q->where('users.id', $user->id))->first();

        if (!$group || !$group->AFFECT) {
            return redirect()->back()->with('error', 'لم يتم العثور على AFFECT أو مجموعة مرتبطة بالمستخدم.');
        }

        $affect = $group->AFFECT;

        $year = $request->get('year');
        $month = $request->get('month');

        $setting = MonthlyAbsenceSetting::where('year', $year)
            ->where('month', $month)
            ->where('is_open', true)
            ->first();

        $currentAdm = $request->get('adm', '');

        $employeesQuery = Employee::with([
            'group',
            'monthlyAbsences' => function ($query) use ($setting) {
                if ($setting) {
                    $query->where('month', $setting->month)
                        ->where('year', $setting->year);
                }
            }
        ])
            ->orderBy('ADM');

        if (strlen($affect) === 6) {
            $employeesQuery->where('AFFECT', 'like', $affect . '%');
        } else {
            $parentAffect = substr($affect, 0, 6);
            $employeesQuery->where(function ($q) use ($parentAffect) {
                $q->where('AFFECT', $parentAffect)
                    ->whereIn('ADM', [1, 2]); 
            });
        }
        
        if (strlen($affect) === 6) {
            $employeesQuery->where('AFFECT', 'like', $affect . '%');
        } else {
            $employeesQuery->where('PRIMAIRE', $affect);
        }

        if ($currentAdm !== '') {
            $employeesQuery->where('ADM', $currentAdm);
        }

        $employees = $employeesQuery->get();

        $departments = DB::table('departments')
            ->select(
                'departments.ADM',
                'departments.name',
                DB::raw('COUNT(employees.MATRI) as employee_count')
            )
            ->join('employees', 'departments.ADM', '=', 'employees.ADM')
            ->where(function ($query) use ($affect) {
                if (strlen($affect) === 6) {
                    $query->where('employees.AFFECT', 'like', $affect . '%');
                } else {
                    $query->where('employees.PRIMAIRE', $affect);
                }
            })
            ->groupBy('departments.ADM', 'departments.name')
            ->orderBy('departments.name')
            ->get();


        if (!$setting) {
            return view('monthly_absences.create', compact('employees', 'setting', 'affect', 'currentAdm', 'departments'))
                ->with('warning', 'لا يوجد شهر مفتوح للحجز حالياً.');
        }

        return view('monthly_absences.create', compact('employees', 'setting', 'affect', 'currentAdm', 'departments'));
    }



    /**
     * حفظ بيانات الغياب لموظف.
     */
    public function store(Request $request)
    {
        $request->validate([
            'MATRI' => 'required',
            'absence_days' => 'required|numeric|min:0',
            'absence_reason' => 'nullable|string|max:255',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000', // عدّل حسب الحاجة
        ]);

        $setting = MonthlyAbsenceSetting::where('is_open', true)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->first();

        if (!$setting) {
            return response()->json(['success' => false, 'message' => 'هذا الشهر غير مفتوح للحجز']);
        }

        $employee = Employee::where('MATRI', $request->MATRI)->first();

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'لم يتم العثور على الموظف']);
        }

        MonthlyAbsence::updateOrCreate(
            [
                'MATRI' => $employee->MATRI,
                'month' => $setting->month,
                'year' => $setting->year,
            ],
            [
                'absence_days' => $request->absence_days,
                'absence_reason' => $request->absence_reason,
            ]
        );

        return response()->json(['success' => true, 'message' => 'تم حفظ بيانات الغياب بنجاح']);
    }

    /**
     * مسح كل بيانات الغياب لشهر معين.
     */
    public function clear(Request $request)
    {
        $request->validate([
            'month' => 'required|numeric|min:1|max:12',
            'year' => 'required|numeric|min:2000|max:2100',
        ]);

        MonthlyAbsence::where('month', $request->month)->where('year', $request->year)->delete();

        return redirect()->route('monthly_absences.months')->with('success', 'تم مسح بيانات الغيابات بنجاح.');
    }

    /**
     * تصدير بيانات الغياب إلى Excel.
     */
    public function export($year, $month)
    {
        $settings = MonthlyAbsenceSetting::where('year', $year)
            ->where('month', $month)
            ->get(); // يرجع Collection

        if ($settings->isEmpty()) {
            return redirect()->route('monthly_absences.months')
                ->with('error', 'لم يتم العثور على إعدادات الغيابات');
        }

        // خذ أول عنصر من النتائج
        $setting = $settings->first();

        return Excel::download(
            new MonthlyAbsencesExport($setting->month, $setting->year),
            "monthly_absences_{$setting->month}_{$setting->year}.xlsx"
        );
    }



    /**
     * عرض تفاصيل الغيابات لشهر محدد.
     */
    public function details($year, $month)
    {
        $absences = MonthlyAbsence::with('employee')
            ->where('year', $year)
            ->where('month', $month)
            ->get();

        return view('monthly_absences.details', compact('absences', 'year', 'month'));
    }


    /**
     * قائمة الأشهر بالعربية.
     */
    private function arabicMonths()
    {
        return [
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
            12 => 'ديسمبر',
        ];
    }
}
