<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Models\Group;


class RegisteredUserController extends Controller
{
    public function create()
    {
        $mainGroups = Group::whereNull('parent_id')->get();
        return view('auth.register', compact('mainGroups'));
    }
    public function getSubGroups($mainGroupId)
    {
        $subGroups = Group::where('parent_id', $mainGroupId)->get();
        return response()->json($subGroups);
    }
    public function filterMainGroups(Request $request)
    {
        $parentIds = $request->input('parent_ids', []);

        // جلب المجموعات التي id نفسها ضمن القيم المطلوبة
        $groups = Group::whereIn('id', $parentIds)->get();

        return response()->json($groups);
    }

    public function filterByType(Request $request)
    {
        $type = $request->input('group_type');
        $userType = $request->input('user_type'); // ← اجلب نوع المستخدم أيضًا

        $query = Group::query()
            ->where('type', $type)
            ->whereNull('parent_id');

        // عند اختيار manager، استثنِ id = 27 فقط
        if ($userType === 'manager' && $type === 'education') {
            $query->where('id', '!=', 27);
        }

        $groups = $query->get();

        return response()->json($groups);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:users|digits:10',
            'email' => 'required|email|unique:users|max:255',
            'username' => 'required|string|max:255',
            'user_type' => 'required|in:admin,office_head,director,manager,inspector',
            'main_group' => [
                Rule::requiredIf(in_array($request->user_type, ['admin', 'office_head', 'director', 'manager', 'inspector'])),
                'exists:groups,id',
            ],
            'sub_group' => [
                Rule::requiredIf(in_array($request->user_type, ['office_head', 'director', 'manager', 'inspector'])),
                'exists:groups,id',
                Rule::unique('users', 'sub_group')->where(function ($query) use ($request) {
                    if (in_array($request->user_type, ['director', 'manager'])) {
                        return $query->where('role', $request->user_type);
                    }
                    return $query;
                }),
            ],

            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'يرجى إدخال الاسم و اللقب.',
            'phone.required' => 'يرجى إدخال رقم الهاتف.',
            'phone.unique' => 'رقم الهاتف مستخدم من قبل.',
            'phone.digits' => 'رقم الهاتف يجب أن يكون 10 أرقام.',
            'email.required' => 'يرجى إدخال البريد الإلكتروني.',
            'email.email' => 'يرجى إدخال عنوان بريد إلكتروني صحيح.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم مسبقاً.',
            'username.required' => 'يرجى إدخال اسم المستخدم.',
            'user_type.required' => 'يرجى اختيار نوع المستخدم.',
            'user_type.in' => 'نوع المستخدم غير صالح.',
            'main_group.required' => 'يرجى إدخال طبيعة المؤسسة أو الإدارة أو الهيئة.',
            'main_group.exists' => 'طبيعة المؤسسة غير صحيحة.',
            'sub_group.required' => 'يرجى إدخال اسم المؤسسة أو الإدارة أو الهيئة.',
            'sub_group.unique' => 'تم التسجيل بهذه المؤسسة أو الإدارة أو الهيئة مسبقا.',
            'sub_group.exists' => 'اسم المؤسسة أو الهيئة غير صحيح.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
            'password.confirmed' => 'تأكيد كلمة المرور غير مطابق.',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 8 أحرف.',
        ]);

        $role = $request->user_type;

        $userData = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'username' => $request->username,
            'main_group' => $request->main_group,
            'sub_group' => $request->sub_group,
            'password' => Hash::make($request->password),
            'role' => $role,
            'is_active' => false,
        ];

        $user = User::create($userData);

        $user->groups()->attach($request->sub_group);

        event(new Registered($user));

        return redirect()->route('login')->with('success', 'تم تسجيلك بنجاح. في انتظار تفعيل حسابك.');
    }



}



