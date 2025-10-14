<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeesImport;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('group')->get();
        $grades = Grade::all();
        return view('employees.index', compact('employees', 'grades'));
    }
    public function create()
    {
        $grades = Grade::all();
        $groups = Group::with('children')->whereNull('parent_id')->get(['id', 'name']);
        $employees = Employee::with('group')->get();

        return view('employees.add', compact('employees', 'groups', 'grades'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'NOM' => 'required|string|max:20',
            'PRENOM' => 'required|string|max:20',
            'NOMA' => 'required|string|max:20',
            'PRENOMA' => 'required|string|max:20',
            'SITFAM' => 'required|string|max:20',
            'ENF10' => 'nullable|integer|min:0',
            'MATRI' => 'required|string|max:20|unique:employees,MATRI',
            'CLECPT' => 'required|string|max:2',
            'NUMSS' => 'required|string|max:20',
            'CODFONC' => 'required|string|max:10',
            'ADM' => 'required|string|max:10',
            'DATNAIS' => 'required|date_format:Y-m-d',
            'DATENT' => 'required|date_format:Y-m-d',
            'ECH' => 'required|integer|min:0|max:12', // تم تغييره ليكون عددًا صحيحًا
            'AFFECT' => 'required|string|max:8|exists:groups,AFFECT',
        ]);

        try {
            Employee::create($data);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => '❌ حدث خطأ أثناء إضافة الموظف. تأكد من صحة البيانات.'])->withInput();
        }

        return redirect()->route('employees.index')->with('success', '✅ تم إضافة الموظف بنجاح');
    }


    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $grades = Grade::all();
        $employeeGrade = Grade::where('codtab', $employee->CODFONC)->first();
        $groups = Group::whereIn('parent_id', [27, 118, 153])
            ->orWhere('AFFECT', 570000)
            ->get();

        return view('employees.edit', compact('employee', 'groups', 'grades', 'employeeGrade'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->update($request->all());
        return redirect()->back()->with('success', 'تم تحديث بيانات الموظف بنجاح.');
    }


    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return redirect()->back()->with('success', 'تم حذف الموظف بنجاح.');
    }

    public function import(Request $request)
    {
        ini_set('memory_limit', '1024M'); // يزيد الحد إلى 1 جيجابايت
        $file = $request->file('file');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('employees')->truncate();
        Excel::import(new EmployeesImport, $file);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return redirect()->back()->with('success', 'تم استيراد البيانات بنجاح');
    }
    public function statistics()
    {
        $gradesWithCounts = Grade::withCount('employees')->get();
        $groupsWithEmployees = Group::whereIn('type', ['education', 'admin'])
            ->has('employees')
            ->withCount('employees')
            ->get();

        return view('employees.statistics', compact('gradesWithCounts', 'groupsWithEmployees'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $employees = Employee::with('grade')
            ->where('NOMA', 'LIKE', "%{$query}%")
            ->orWhere('PRENOMA', 'LIKE', "%{$query}%")
            ->orWhere('MATRI', 'LIKE', "%{$query}%")
            ->limit(70)
            ->get();

        return response()->json($employees);
    }



    public function selectGradeForCards()
    {
        $grades = Grade::orderBy('name')->get();
        return view('employees.select-grade', compact('grades'));
    }

    public function loadEmployeesByGrade(Request $request)
    {
        $request->validate([
            'grade_code' => 'required',
            'per_page' => 'nullable|integer|in:8,10,12',
        ]);

        $gradeCode = $request->grade_code;
        $perPage = $request->per_page ?? 10;

        $grades = Grade::orderBy('name')->get();

        $employees = Employee::with('grade')
            ->whereHas('grade', function ($query) use ($gradeCode) {
                $query->where('codtab', $gradeCode);
            })
            ->orderBy('NOMA')
            ->orderBy('PRENOMA')
            ->get();
        return view('employees.select-grade', compact('grades', 'employees', 'gradeCode', 'perPage'));

    }

   public function printSelectedEmployees(Request $request)
{
    // الحالة الأولى: عند الفتح المباشر (GET)
    if ($request->isMethod('get')) {

        // لو جاء ids في الرابط
        $ids = $request->query('ids');
        if ($ids) {
            $idsArray = explode(',', $ids);

            $employees = Employee::with('grade')
                ->whereIn('id', $idsArray)
                ->orderBy('NOMA')
                ->orderBy('PRENOMA')
                ->get();

            // نحفظ نفس الـIDs في الجلسة لاستخدامها لاحقًا
            session(['selected_employees' => $idsArray]);

            return view('employees.card', compact('employees'));
        }

        // لو ما فيه ids → عرض الكل
        $employees = Employee::with('grade')
            ->orderBy('NOMA')
            ->orderBy('PRENOMA')
            ->get();

        return view('employees.card', compact('employees'));
    }

    // الحالة الثانية: الطباعة من الفورم (POST)
    $ids = $request->input('employee_ids');
    if (!$ids) {
        return redirect()->back()->with('error', 'الرجاء اختيار موظفين على الأقل');
    }

    $employees = Employee::with('grade')
        ->whereIn('id', $ids)
        ->orderBy('NOMA')
        ->orderBy('PRENOMA')
        ->get();

    // تخزينهم في الجلسة
    session(['selected_employees' => $ids]);

    return view('employees.card', compact('employees'));
}

    public function showBack()
    {
        // استرجاع الموظفين المختارين من الجلسة
        $selectedIds = session('selected_employees', []);

        if (!empty($selectedIds)) {
            $employees = Employee::with('grade')
                ->whereIn('id', $selectedIds)
                ->orderBy('NOMA')
                ->orderBy('PRENOMA')
                ->get();
        } else {
            return redirect()->route('cards.select-grade')
                ->with('error', 'لا توجد بطاقات لعرضها. الرجاء اختيار الموظفين أولاً.');
        }

        if ($employees->isEmpty()) {
            return redirect()->route('cards.select-grade')
                ->with('error', 'لا توجد بطاقات لعرضها. الرجاء اختيار الموظفين أولاً.');
        }
        session(['selected_employees' => $employees->pluck('id')->toArray()]);

        // عرض واجهة الوجه الخلفي
        return view('employees.cardsback', compact('employees'));
    }




}
