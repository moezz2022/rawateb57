<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\PrimeScolarite;
use App\Models\PrimeScolariteSetting;
use App\Models\Group;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PrimeScolariteExport;

class PrimeScolariteController extends Controller
{
    /**
     * عرض إعدادات الأشهر (جدول الأشهر المضافة مع فتح/غلق).
     */
    public function primesettings(Request $request)
    {

        $yearSettings = PrimeScolariteSetting::orderBy('year', 'desc')->get();

        return view('prime_scolarité.primesettings', compact('yearSettings'));
    }


    public function settings(Request $request)
    {
        $yearSettings = PrimeScolariteSetting::orderBy('year', 'desc')->get();

        return view('prime_scolarité.settings', compact('yearSettings'));
    }


    public function storeSetting(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2000',
        ]);

        try {
            $setting = PrimeScolariteSetting::firstOrCreate(
                ['year' => (int) $validated['year']],
                ['is_open' => false]
            );

            if (!$setting->wasRecentlyCreated) {
                return back()->with('error', 'منحة التمدرس لهذه السنة مضافة بالفعل.');
            }

            return redirect()
                ->route('prime_scolarité.primesettings', ['year' => $validated['year']])
                ->with('success', 'تمت إضافة منحة التمدرس بنجاح.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'تعذر إضافة المنحة. يرجى المحاولة لاحقًا.');
        }
    }

    public function toggle($id)
    {
        $setting = PrimeScolariteSetting::findOrFail($id);
        $setting->is_open = !$setting->is_open;
        $setting->save();

        return back()->with('success', 'تم تحديث حالة المنحة.');
    }

    /**
     * عرض صفحة الحجز للموظفين.
     */
    public function index(Request $request)
    {
        $setting = PrimeScolariteSetting::where('is_open', true)->first();

        if (!$setting) {
            return view('prime_scolarité.index', [
                'employees' => collect(),
                'departments' => Department::withCount('employees')->get(),
                'settings' => collect(),
                'setting' => null,
                'currentAdm' => '',
            ]);
        }

        $year = $setting->year;
        $settings = PrimeScolariteSetting::where('is_open', true)->get();

        $departments = Department::withCount('employees')->get();
        $currentAdm = $request->query('adm', '');

        $employeesQuery = Employee::with([
            'primescolarites' => fn($q) => $q->where('year', $year),
            'grade',
            'group'
        ]);

        if ($currentAdm !== '') {
            $employeesQuery->where('ADM', $currentAdm);
        }

        $employees = $employeesQuery->get();

        return view('prime_scolarité.index', compact(
            'employees',
            'departments',
            'settings',
            'setting',
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
    $setting = PrimeScolariteSetting::where('year', $year)
        ->where('is_open', true)
        ->first();

    $employeesQuery = Employee::with([
        'group',
        'grade',
        'primescolarites' => fn($q) => $setting ? $q->where('year', $setting->year) : null
    ])->orderBy('ADM');

    // فلترة حسب AFFECT
    if (strlen($affect) === 6) {
        $employeesQuery->where('AFFECT', 'like', $affect . '%');
    } else {
        $employeesQuery->where('PRIMAIRE', $affect);
    }

    $departments = Department::select('ADM', 'name')
        ->withCount([
            'employees as employee_count' => function ($q) use ($affect) {
                if (strlen($affect) === 6) {
                    $q->where('AFFECT', 'like', $affect . '%');
                } else {
                    $q->where('PRIMAIRE', $affect);
                }
            }
        ])
        ->orderBy('name')
        ->get();

    // تحديد الإدارة الحالية
    $currentAdm = $request->adm ?? ($departments->count() > 0 ? $departments->first()->ADM : null);

    // فلترة الموظفين حسب الإدارة الحالية
    if ($currentAdm) {
        $employeesQuery->where('ADM', $currentAdm);
    } else {
        $employeesQuery->whereRaw('1 = 0'); // لا يرجع أي موظف إذا لم توجد إدارة
    }

    // هنا نستدعي get بعد كل الفلاتر
    $employees = $employeesQuery->get();

    if (!$setting) {
        return view('prime_scolarité.create', compact('employees', 'setting', 'affect', 'currentAdm', 'departments'))
            ->with('warning', 'لا يوجد منحة مفتوح للحجز حالياً.');
    }

    return view('prime_scolarité.create', compact('employees', 'setting', 'affect', 'currentAdm', 'departments'));
}


    public function show(Request $request, $year)
    {
        $user = auth()->user();
        $group = Group::whereHas('users', fn($q) => $q->where('users.id', $user->id))->first();

        if (!$group || !$group->AFFECT) {
            return redirect()->back()->with('error', 'لم يتم العثور على AFFECT أو مجموعة مرتبطة بالمستخدم.');
        }

        $affect = $group->AFFECT;


        // هنا نجيب الإعداد سواء مفتوح أو مغلق
        $setting = PrimeScolariteSetting::where('year', $year)->first();

        $currentAdm = $request->get('adm', '');

        $employeesQuery = Employee::with([
            'group',
            'grade',
            'primescolarites' => fn($q) => $q->where('year', $year)
        ])->orderBy('ADM');

        // فلترة حسب AFFECT
        if (strlen($affect) === 6) {
            $employeesQuery->where('AFFECT', 'like', $affect . '%');
        } else {
            $employeesQuery->where('PRIMAIRE', $affect);
        }

        if ($currentAdm !== '') {
            $employeesQuery->where('ADM', $currentAdm);
        }

        $employees = $employeesQuery->get();

        $departments = Department::select('ADM', 'name')
            ->withCount([
                'employees as employee_count' => function ($q) use ($affect) {
                    if (strlen($affect) === 6) {
                        $q->where('AFFECT', 'like', $affect . '%');
                    } else {
                        $q->where('PRIMAIRE', $affect);
                    }
                }
            ])
            ->orderBy('name')
            ->get();


        // إذا مغلق → نعرض صفحة المعاينة فقط
        return view('prime_scolarité.show', compact('employees', 'setting', 'affect', 'currentAdm', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'MATRI' => 'required',
            'year' => 'required|integer|min:2000',
            'ENF' => 'nullable|numeric|min:0',
            'ENFSCO' => 'nullable|numeric|min:0',
        ]);

        $setting = PrimeScolariteSetting::where('is_open', true)
            ->where('year', $request->year)
            ->first();

        if (!$setting) {
            return response()->json(['success' => false, 'message' => 'الحجز غير مفتوح لهذه السنة']);
        }

        $employee = Employee::where('MATRI', $request->MATRI)->first();

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'لم يتم العثور على الموظف']);
        }

        PrimeScolarite::updateOrCreate(
            [
                'MATRI' => $employee->MATRI,
                'year' => $setting->year,
            ],
            [
                'ENF' => $request->ENF ?? 0,
                'ENFSCO' => $request->ENFSCO ?? 0,
            ]
        );

        return response()->json(['success' => true, 'message' => 'تم حفظ بيانات منحة التمدرس بنجاح']);
    }


    public function export($year)
    {
        $settings = PrimeScolariteSetting::where('year', $year)
            ->get(); // يرجع Collection

        if ($settings->isEmpty()) {
            return redirect()->route('prime_scolarité.settings')
                ->with('error', 'لم يتم العثور على إعدادات المنحة');
        }

        // خذ أول عنصر من النتائج
        $setting = $settings->first();

        return Excel::download(
            new PrimeScolariteExport($setting->year),
            "prime_scolarites_{$setting->year}.xlsx"
        );
    }





}
