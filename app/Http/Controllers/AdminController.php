<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Grade;

class AdminController extends Controller
{
    public function show(Request $request)
    {
        $currentUser = Auth::user();
        $grades = Grade::all();

        $currentAdm = $request->adm ?? '';

        $groups = Group::where(function ($query) {
            $query->whereNotNull('parent_id')
                ->orWhere('AFFECT', 570000);
        })->get();

        $userAffects = Group::whereIn('id', function ($query) use ($currentUser) {
            $query->select('group_id')
                ->from('user_group')
                ->where('user_id', $currentUser->id);
        })->pluck('AFFECT')->toArray();

        if (count($userAffects) === 0) {
            return redirect()->back()->with('error', 'لا يوجد مؤسسة مرتبطة بحسابك.');
        }

        $usersQuery = Employee::with(['group', 'primaireGroup'])
            ->where(function ($query) use ($userAffects) {
                foreach ($userAffects as $affect) {
                    if (strlen($affect) === 6) {
                        // مؤسسة أم → نفسها + الأبناء
                        $query->orWhere('AFFECT', 'like', $affect . '%');
                    } else {
                        // مؤسسة ابن → موظفي الأم لكن يظهروا هنا حتى بدون PRIMAIRE
                        $parentAffect = substr($affect, 0, 6);

                        $query->orWhere(function ($q) use ($parentAffect, $affect) {
                            $q->where('AFFECT', $parentAffect)
                                ->whereIn('employees.ADM', [1, 2]);
                            //  $q->where('PRIMAIRE', $affect);
                        });
                    }
                }
            });

        if (!empty($currentAdm)) {
            $usersQuery->where('ADM', $currentAdm);
        }

        $users = $usersQuery->get();


        if ($users->isEmpty()) {
            return redirect()->back()->with('error', 'لا يوجد موظفون مرتبطون بهذه المؤسسة.');
        }

        $isParentGroup = collect($userAffects)->contains(function ($affect) {
            return strlen($affect) === 6;
        });

        $departments = DB::table('departments')
            ->select(
                'departments.ADM',
                'departments.name',
                DB::raw('COUNT(employees.MATRI) as users_count')
            )
            ->join('employees', 'departments.ADM', '=', 'employees.ADM')
            ->where(function ($query) use ($userAffects, $isParentGroup) {
                foreach ($userAffects as $affect) {
                    if (strlen($affect) === 6) {
                        // الأم: تجيب نفسها + الأبناء
                        $query->orWhere('employees.AFFECT', 'like', $affect . '%');
                    } else {
                        // الابن: يرجع موظفي الأم فقط
                        $parentAffect = substr($affect, 0, 6);
                        $query->orWhere(function ($q) use ($parentAffect) {
                            $q->where('employees.AFFECT', $parentAffect)
                                ->whereIn('employees.ADM', [1, 2]); // فلترة الإدارات المسموح بها
                        });
                    }
                }
            })
            ->groupBy('departments.ADM', 'departments.name')
            ->orderBy('departments.name')
            ->get();

        return view('users.indexuser', compact(
            'users',
            'groups',
            'grades',
            'departments',
            'currentAdm'
        ))->with('currentAffect', $userAffects[0]); // لو عنده مؤسسة وحدة

    }


    public function assign(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'primaire_affect' => 'required|string',
        ]);

        // إذا كان الموظف مسند بالفعل لمؤسسة أخرى
        if (!empty($employee->PRIMAIRE) && $employee->PRIMAIRE !== $request->primaire_affect) {
            return redirect()->back()->with('error', 'الموظف مسند بالفعل لمؤسسة أخرى.');
        }

        // إسناد الموظف
        $employee->update([
            'PRIMAIRE' => $request->primaire_affect,
        ]);

        return redirect()->back()->with('success', 'تم إسناد الموظف بنجاح.');
    }


    public function unassign($id)
    {
        $employee = Employee::findOrFail($id);

        if (empty($employee->PRIMAIRE)) {
            return redirect()->back()->with('error', 'الموظف غير مسند لأي مؤسسة.');
        }

        // إلغاء الإسناد
        $employee->update([
            'PRIMAIRE' => null,
        ]);

        return redirect()->back()->with('success', 'تم إلغاء انتماء الموظف بنجاح.');
    }




    public function index(): View
    {
        $users = User::with(['mainGroup', 'subGroup'])->get();
        return view('users.activeuser', compact('users'));
    }
    public function create()
    {
        $mainGroups = Group::whereNull('parent_id')->get();
        return view('users.activeuser', compact('mainGroups'));
    }
    public function getSubGroups($mainGroupId)
    {
        $subGroups = Group::where('parent_id', $mainGroupId)
            ->select('id', 'name')
            ->get();
        return response()->json($subGroups);
    }
    public function activateUser($id)
    {
        if (auth()->user()->role != 'admin') {
            return redirect()->route('dashboard')->with('error', 'ليس لديك صلاحيات لتفعيل الحسابات.');
        }
        $user = User::findOrFail($id);
        $user->is_active = true;
        $user->save();
        return response()->json(['message' => 'تم تفعيل حساب المستخدم بنجاح.']);
    }
    public function deactivateUser($id)
    {
        if (auth()->user()->role != 'admin') {
            return redirect()->route('dashboard')->with('error', 'ليس لديك صلاحيات لتفعيل الحسابات.');
        }
        $user = User::findOrFail($id);
        $user->is_active = false;
        $user->save();
        return response()->json(['message' => 'تم تعطيل حساب المستخدم بنجاح.']);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $mainGroups = Group::whereNull('parent_id')->get();
        $subGroups = Group::where('parent_id', $user->main_group)->get();
        return view('users.edit', compact('user', 'mainGroups', 'subGroups'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|digits:10',
            'email' => 'required|email|max:255',
            'username' => 'required|string|max:255',
            'main_group' => 'required|exists:groups,id',
            'sub_group' => 'required|exists:groups,id',

        ]);
        $user = User::findOrFail($id);
        $data = $request->only(['name', 'phone', 'email', 'username', 'main_group', 'sub_group']);
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);
        $subGroupId = $request->sub_group;
        $userGroup = DB::table('user_group')
            ->where('user_id', $user->id)
            ->first();
        if ($userGroup) {
            DB::table('user_group')
                ->where('user_id', $user->id)
                ->update(['group_id' => $subGroupId]);
        } else {
            DB::table('user_group')->insert([
                'user_id' => $user->id,
                'group_id' => $subGroupId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return redirect()->route('users.activeuser.index')->with('success', 'تم تحديث معلومات الحساب بنجاح.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('status', 'تم حذف المستخدم بنجاح.');
    }
    public function showTransferPage(Request $request)
    {
        $grades = Grade::all();
        $groups = Group::where(function ($query) {
            $query->whereIn('parent_id', [118, 153])
                ->orWhere('AFFECT', 570000);
        })->get();

        $employees = collect();
        $employee = null;

        if ($request->filled('search')) {
            $search = trim(preg_replace('/\s+/', ' ', $request->input('search')));
            // ننظف البحث من المسافات الزائدة

            $employees = Employee::with('group')
                ->where(function ($query) use ($search) {
                    $query->where('NOMA', 'LIKE', "%{$search}%")
                        ->orWhere('PRENOMA', 'LIKE', "%{$search}%")
                        ->orWhere('MATRI', $search)
                        ->orWhereRaw(
                            "REPLACE(CONCAT(TRIM(NOMA), ' ', TRIM(PRENOMA)), ' ', '') LIKE ?",
                            ["%" . str_replace(' ', '', $search) . "%"]
                        );
                })
                ->get();

            if ($employees->count() === 1) {
                $employee = $employees->first();
            }
        }

        return view('users.transfer', compact('employees', 'groups', 'grades', 'employee'));
    }

    public function updateGroup(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'AFFECT' => 'required|string',
            'CODFONC' => 'required|string'
        ]);
        $employee = Employee::find($request->input('employee_id'));
        if (!$employee) {
            return redirect()->route('users.transfer')->with('error', 'لم يتم العثور على الموظف.');
        }

        $selectedGroup = Group::where('AFFECT', $request->input('AFFECT'))->first();
        $newAffectId = $selectedGroup ? $selectedGroup->AFFECT : null;
        if (!$newAffectId) {

            return redirect()->route('users.transfer')->with('error', 'المؤسسة المستقبلة غير صحيحة.');
        }
        $employee->update([
            'AFFECT' => $newAffectId,
            'CODFONC' => $request->input('CODFONC'),
        ]);
        $employee->refresh();
        return back()->with('success', 'تم تحويل الموظف بنجاح!');
    }



}



