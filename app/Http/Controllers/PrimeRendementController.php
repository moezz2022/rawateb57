<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\PrimeRendement;
use App\Models\Department;
use App\Models\Group;
use App\Models\RendementSetting;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PrimeRendementsExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PrimeRendementController extends Controller
{

    public function create(Request $request)
    {
        $user = auth()->user();

        // جلب الفترة المفتوحة
        $rendementSetting = RendementSetting::where('is_open', true)
            ->when($request->year, fn($q) => $q->where('year', $request->year))
            ->when($request->quarter, fn($q) => $q->where('quarter', $request->quarter))
            ->orderBy('year', 'desc')
            ->orderBy('quarter', 'asc')
            ->first();

        if (!$rendementSetting) {
            return redirect()->route('prime_rendements.rndmsettings')
                ->with('error', 'الفترة غير متاحة للحجز.');
        }

        $year = $rendementSetting->year;
        $quarter = $rendementSetting->quarter;

        // جلب AFFECT للمجموعة الخاصة بالمستخدم
        $affect = Group::whereHas('users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })->value('AFFECT');

        if (!$affect) {
            return redirect()->back()->with('error', 'لم يتم العثور على AFFECT لمجموعتك.');
        }

        // جلب الإدارات وعدد الموظفين
        $departments = Department::select(
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

        // تحديد الإدارة الحالية: أول إدارة متاحة إذا لم يحدد المستخدم أي إدارة
        $currentAdm = $request->adm ?? ($departments->count() > 0 ? $departments->first()->ADM : null);

        // بناء query الموظفين
        $employeesQuery = Employee::with([
            'primeRendements' => function ($q) use ($year, $quarter) {
                $q->where('year', $year)
                    ->where('quarter', $quarter);
            }
        ])
            ->orderBy('ADM')
            ->orderBy('CODFONC');

        if (strlen($affect) === 6) {
            $employeesQuery->where('AFFECT', 'like', $affect . '%');
        } else {
            $employeesQuery->where('PRIMAIRE', $affect);
        }

        // فلترة الموظفين حسب الإدارة الحالية
        if ($currentAdm) {
            $employeesQuery->where('ADM', $currentAdm);
        } else {
            $employeesQuery->whereRaw('1 = 0'); // لا يرجع أي موظف إذا لم توجد إدارة
        }

        $employees = $employeesQuery->distinct()->get();

        // تحديد الأشهر للفترة
        $periodMonths = match ($rendementSetting->period) {
            'الأول' => [1, 2, 3],
            'الثاني' => [4, 5, 6],
            'الثالث' => [7, 8, 9],
            'الرابع' => [10, 11, 12],
            default => [],
        };

        // حساب الغيابات
        $absences = DB::table('monthly_absences')
            ->select('MATRI', DB::raw('SUM(absence_days) as total_absences'))
            ->whereIn('month', $periodMonths)
            ->where('year', $year)
            ->groupBy('MATRI')
            ->pluck('total_absences', 'MATRI')
            ->mapWithKeys(fn($v, $k) => [(string) $k => $v]);

        foreach ($employees as $employee) {
            $employee->total_absences_period = $absences[(string) $employee->MATRI] ?? 0;
        }

        return view('prime_rendements.create', [
            'employees' => $employees,
            'eligibleFor40' => config('rendement.eligible_codes'),
            'setting' => $rendementSetting,
            'currentAdm' => $currentAdm,
            'departments' => $departments,
            'year' => $year,
            'quarter' => $quarter,
        ]);
    }




    public function show(Request $request)
    {
        $user = auth()->user();

        // جلب الفترة المفتوحة
        $rendementSetting = RendementSetting::when($request->year, fn($q) => $q->where('year', $request->year))
            ->when($request->quarter, fn($q) => $q->where('quarter', $request->quarter))
            ->orderBy('year', 'desc')
            ->orderBy('quarter', 'asc')
            ->first();
        if (!$rendementSetting) {
            return redirect()->back()->with('error', 'لم يتم العثور على الفترة المطلوبة.');
        }

        $year = $rendementSetting->year;
        $quarter = $rendementSetting->quarter;

         // جلب AFFECT للمجموعة الخاصة بالمستخدم
        $affect = Group::whereHas('users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })->value('AFFECT');

        if (!$affect) {
            return redirect()->back()->with('error', 'لم يتم العثور على AFFECT لمجموعتك.');
        }

        // جلب الإدارات وعدد الموظفين
        $departments = Department::select(
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

        // تحديد الإدارة الحالية: أول إدارة متاحة إذا لم يحدد المستخدم أي إدارة
        $currentAdm = $request->adm ?? ($departments->count() > 0 ? $departments->first()->ADM : null);

        // بناء query الموظفين
        $employeesQuery = Employee::with([
            'primeRendements' => function ($q) use ($year, $quarter) {
                $q->where('year', $year)
                    ->where('quarter', $quarter);
            }
        ])
            ->orderBy('ADM')
            ->orderBy('CODFONC');

        if (strlen($affect) === 6) {
            $employeesQuery->where('AFFECT', 'like', $affect . '%');
        } else {
            $employeesQuery->where('PRIMAIRE', $affect);
        }

        // فلترة الموظفين حسب الإدارة الحالية
        if ($currentAdm) {
            $employeesQuery->where('ADM', $currentAdm);
        } else {
            $employeesQuery->whereRaw('1 = 0'); // لا يرجع أي موظف إذا لم توجد إدارة
        }

        $employees = $employeesQuery->distinct()->get();

        // تحديد الأشهر للفترة
        $periodMonths = match ($rendementSetting->period) {
            'الأول' => [1, 2, 3],
            'الثاني' => [4, 5, 6],
            'الثالث' => [7, 8, 9],
            'الرابع' => [10, 11, 12],
            default => [],
        };

        // حساب الغيابات
        $absences = DB::table('monthly_absences')
            ->select('MATRI', DB::raw('SUM(absence_days) as total_absences'))
            ->whereIn('month', $periodMonths)
            ->where('year', $year)
            ->groupBy('MATRI')
            ->pluck('total_absences', 'MATRI')
            ->mapWithKeys(fn($v, $k) => [(string) $k => $v]);

        foreach ($employees as $employee) {
            $employee->total_absences_period = $absences[(string) $employee->MATRI] ?? 0;
        }

        return view('prime_rendements.show', [
            'employees' => $employees,
            'eligibleFor40' => config('rendement.eligible_codes'),
            'setting' => $rendementSetting,
            'currentAdm' => $currentAdm,
            'departments' => $departments,
            'year' => $year,
            'quarter' => $quarter,
        ]);
    }

    public function store(Request $request)
    {

        $year = $request->get('year');
        $quarter = $request->get('quarter');

        $setting = RendementSetting::where('year', $year)
            ->where('quarter', $quarter)
            ->where('is_open', true)
            ->first();

        if (!$setting) {
            return response()->json(['error' => 'الفترة غير متاحة'], 403);
        }

        if (!$request->has('employees') || !is_array($request->employees)) {
            return response()->json(['error' => 'لا توجد بيانات مرسلة'], 400);
        }

        foreach ($request->employees as $emp) {
            if (!isset($emp['MATRI'])) {
                continue;
            }

            $employee = Employee::where('MATRI', $emp['MATRI'])->first();
            if (!$employee) {
                continue;
            }

            $maxMark = in_array($employee->CODFONC, config('rendement.eligible_codes')) ? 40 : 30;

            PrimeRendement::updateOrCreate(
                [
                    'MATRI' => $emp['MATRI'],
                    'year' => $year,
                    'quarter' => $quarter,
                ],
                [
                    'mark' => min((int) ($emp['mark'] ?? 0), $maxMark),
                    'absence_days' => (int) ($emp['absence_days'] ?? 0),
                    'notes' => $emp['notes'] ?? null,
                    'ADM' => $emp['ADM'] ?? $employee->ADM, // ✅ العمود بحروف كبيرة
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ المردودية بنجاح!',
        ]);
    }



    public function getCurrentPeriod()
    {
        $setting = RendementSetting::first();
        return response()->json(['period' => $setting->period ?? 'غير متوفر']);
    }

    public function exportExcel()
    {
        return Excel::download(new PrimeRendementsExport, 'mardoudia.xlsx');
    }

    public function details($year, $quarter)
    {
        $quarters = [
            1 => 'الأول',
            2 => 'الثاني',
            3 => 'الثالث',
            4 => 'الرابع',
        ];

        $departments = Department::withCount([
            // عدد جميع الموظفين في الإدارة
            'employees as total_employees_count',

            // عدد الموظفين المنقطين فقط
            'employees as scored_employees_count' => function ($q) use ($year, $quarter) {
                $q->whereHas('primeRendements', function ($qq) use ($year, $quarter) {
                    $qq->where('year', $year)->where('quarter', $quarter);
                });
            }
        ])->get();

        return view('prime_rendements.rndmdetail', compact('departments', 'year', 'quarter', 'quarters'));
    }


    public function exportUpdatesSql(Request $request, $year, $quarter)
    {
        $adm = $request->get('adm'); // رقم الإدارة

        $primeRendements = PrimeRendement::with('employee')
            ->where('year', $year)
            ->where('quarter', $quarter)
            ->whereHas('employee', function ($q) use ($adm) {
                if ($adm) {
                    $q->where('ADM', $adm);
                }
            })
            ->get();

        if ($primeRendements->isEmpty()) {
            return redirect()->back()->with('warning', 'لا توجد بيانات للتصدير.');
        }

        $updates = [];
        foreach ($primeRendements as $prime) {
            $MATRI = $prime->employee->MATRI ?? null;
            $ADM = $prime->employee->ADM ?? null;

            if (!$MATRI || !$ADM)
                continue;

            $absenceDays = (int) $prime->absence_days;
            $mark = (float) $prime->mark;
            $jrprime = 90 - $absenceDays;
            $note = $mark / 2;

            // 👇 اسم الجدول حسب الإدارة
            $tableName = "PRPERS" . $ADM;

            $updates[] = "UPDATE $tableName 
SET JRPRIME = $jrprime, 
    JRABS = $absenceDays, 
    TAUX = $mark, 
    NOTE = " . number_format($note, 2, '.', '') . " 
WHERE MATRI = '$MATRI' AND ADM = '$ADM';";
        }

        $sql = implode("\n", $updates);

        $fileName = "PRPERS{$adm}.sql";
        Storage::disk('local')->put($fileName, $sql);

        return response()->download(storage_path("app/$fileName"))->deleteFileAfterSend(true);
    }


    public function reset(Request $request)
    {
        $year = $request->input('year');
        $quarter = $request->input('quarter');
        $adm = $request->input('ADM'); // ✅ الإدارة

        if (!$year || !$quarter || !$adm) {
            return response()->json(['error' => 'بيانات ناقصة'], 400);
        }

        // حذف فقط موظفي هذه الإدارة
        PrimeRendement::where('year', $year)
            ->where('quarter', $quarter)
            ->where('ADM', $adm)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "تم إلغاء البيانات الخاصة بالإدارة $adm فقط",
        ]);
    }




}

