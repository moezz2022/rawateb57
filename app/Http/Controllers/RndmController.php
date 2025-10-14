<?php

namespace App\Http\Controllers;

use App\Models\RwMigrationRndm;
use App\Models\PaperRndm;
use App\Models\Group;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\Grade;
use Illuminate\Support\Facades\View;
class RndmController extends Controller
{



    public function showrndmdetails(Request $request)
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

        $years = RwMigrationRndm::where('status', 1)
            ->where('YEAR', '>=', 2020)
            ->distinct()
            ->orderByDesc('YEAR')
            ->pluck('YEAR');
        if (empty($years)) {
            $years = [date('Y')]; // احتياط
        }
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

        return view('paie.rndm_details', compact('employees', 'departments', 'currentAdm', 'years'))
            ->with('firstAdm', $departments->first());
    }
    private function getUserAffect($user)
    {
        if ($user->role === 'admin') {
            return null;
        }

        return Group::whereHas('users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })
            ->pluck('AFFECT')
            ->first();
    }
    public function rndmDetails($trimestre, $year, $adm)
    {
        try {
            $user = auth()->user();
            $affect = $this->getUserAffect($user);

            // منع وصول مستخدم عادي لإدارة لا تخصه
            if ($affect && !Employee::where('AFFECT', $affect)->where('ADM', $adm)->exists()) {
                return response()->json(['error' => 'غير مسموح لك بالوصول لهذه الإدارة.'], 403);
            }

            $employees = PaperRndm::with(['migration', 'employee'])
                ->where('ADM', $adm)
                ->whereHas(
                    'migration',
                    fn($q) =>
                    $q->where('TRIMESTER', $trimestre)->where('YEAR', $year)
                )
                ->when($affect, fn($q) => $q->whereHas('employee', fn($qq) => $qq->where('AFFECT', $affect)))
                ->get([
                    'MATRI',
                    'ID_MIGRATION',
                    'CATEG',
                    'ECH',
                    'ADM',
                    'SALBASE',
                    'TOTGAIN',
                    'BRUTSS',
                    'RETITS',
                    'RETSS',
                    'NETPAI',
                    'BRUTMENS',
                    'TAUX',
                    'JRPRIME'
                ]);

            if ($employees->isEmpty()) {
                return response()->json(['message' => 'لا توجد بيانات رواتب متاحة لهذه الإدارة.'], 404);
            }

            $rndmData = $employees->map(function ($employee) {
                return [
                    'MATRI' => $employee->MATRI,
                    'Name' => trim("{$employee->employee->NOMA} {$employee->employee->PRENOMA}") ?: 'غير معروف',
                    'CATEG' => $employee->CATEG,
                    'ECH' => $employee->ECH ?? ($employee->employee->ECH ?? '-'),
                    'NBRTRAV' => (int) ($employee->JRPRIME ?? 0),
                    'SALBASE' => number_format($employee->SALBASE ?? 0, 2),
                    'TOTGAIN' => number_format($employee->TOTGAIN ?? 0, 2),
                    'BRUTSS' => number_format($employee->BRUTSS ?? 0, 2),
                    'RETITS' => number_format($employee->RETITS ?? 0, 2),
                    'RETSS' => number_format($employee->RETSS ?? 0, 2),
                    'BRUTMENS' => number_format($employee->BRUTMENS ?? 0, 2),
                    'NETPAI' => number_format($employee->NETPAI ?? 0, 2),
                    'JRPRIME' => $employee->JRPRIME ?? '-',
                    'TAUX' => $employee->TAUX ?? '-',
                ];
            })->unique('MATRI')->values();

            $trimestreNames = [1 => 'الأول', 2 => 'الثاني', 3 => 'الثالث', 4 => 'الرابع'];

            return response()->json([
                'rndmData' => $rndmData,
                'trimestre' => $trimestre,
                'trimestreName' => $trimestreNames[$trimestre] ?? '',
                'year' => $year,
            ]);

        } catch (\Throwable $e) {
            \Log::error('خطأ أثناء جلب قائمة الرواتب: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'حدث خطأ أثناء تحميل قائمة الرواتب.'], 500);
        }
    }



}