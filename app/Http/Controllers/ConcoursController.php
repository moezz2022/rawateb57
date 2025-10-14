<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Concours;
use App\Models\Daira;
use App\Models\Commune;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use mPDF;



class ConcoursController extends Controller
{
    public function getCommunes($daira_id)
    {
        $communes = Commune::where('daira_id', $daira_id)->get();
        return response()->json($communes);
    }
    public function reg()
    {
        $dairas = Daira::all();
        $communes = Commune::all();
        return view('concours.register', compact('dairas', 'communes'));
    }



    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'username' => 'required|string|max:255',
                'password' => 'required|string|min:8',
                'daira_id' => 'required|integer',
                'commune_id' => 'required|integer',
                'con_grade' => 'required|string',
                'diploma' => 'nullable|string',
                'specialty' => 'nullable|string',
                'NomArF' => 'required|string|max:255',
                'PrenomArF' => 'required|string|max:255',
                'gender' => 'required|in:1,0',
                'DateNaiF' => 'required|date',
                'LieuNaiArF' => 'required|string|max:255',
                'birthNum' => 'required|numeric|max:99999999',
                'familyStatus' => 'required|in:1,2,3,4',
                'childrenNumber' => 'nullable|numeric',
                'residenceMunicipality' => 'required|integer',
                'personalAddress' => 'required|string|max:255',
                'phoneNumber' => [
                    'required',
                    'regex:/^0[0-9]{9}$/',
                    Rule::unique('concours', 'phoneNumber'),
                ],
                'serviceState' => 'nullable|in:1,2,3',
                'serviceNum' => 'nullable|string|max:15',
                'servIsDate' => 'nullable|date',
                'residence_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'military_service_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'medical_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'school_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'specialized_training_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'driving_license' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ],
            [
                'phoneNumber.unique' => 'هذا الرقم مستعمل مسبقا.',
                'phoneNumber.regex' => 'رقم الهاتف غير صالح. تأكد من أنه يبدأ بالصفر ويتكون من 10 أرقام.',
                'username.required' => 'اسم المستخدم مطلوب.',
                'username.string' => 'اسم المستخدم يجب أن يكون نصًا.',
                'password.required' => 'كلمة المرور مطلوبة.',
                'password.min' => 'كلمة المرور يجب أن تحتوي على 8 أحرف على الأقل.',
                'daira_id.required' => 'رقم الدائرة مطلوب.',
                'daira_id.integer' => 'رقم الدائرة يجب أن يكون عدد صحيح.',
                'NomArF.required' => 'الاسم العربي مطلوب.',
                'NomArF.max' => 'الاسم العربي يجب ألا يتجاوز 255 حرفًا.',
                'DateNaiF.required' => 'تاريخ الميلاد مطلوب.',
                'DateNaiF.date' => 'تاريخ الميلاد يجب أن يكون بتنسيق صحيح.',
                'LieuNaiArF.required' => 'مكان الميلاد مطلوب.',
                'LieuNaiArF.max' => 'مكان الميلاد يجب ألا يتجاوز 255 حرفًا.',
                'birthNum.required' => 'رقم الميلاد مطلوب.',
                'birthNum.numeric' => 'رقم الميلاد يجب أن يكون عددًا.',
                'familyStatus.required' => 'الحالة العائلية مطلوبة.',
                'familyStatus.in' => 'الحالة العائلية غير صحيحة.',
                'personalAddress.required' => 'العنوان الشخصي مطلوب.',
                'personalAddress.max' => 'العنوان الشخصي يجب ألا يتجاوز 255 حرفًا.',
            ]
        );


        $validatedData['diploma'] = $request->input('diploma') ?: null;
        $validatedData['specialty'] = $request->input('specialty') ?: null;

        try {
            $concours = Concours::create($validatedData);

            foreach ($request->allFiles() as $key => $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('documents', $filename, 'public');
                Document::create([
                    'concours_id' => $concours->id,
                    'type' => $key,
                    'path' => $path,
                ]);
            }

            return redirect()->route('concours.success', ['id' => $concours->id]);

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database Query Error', [
                'error' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
            ]);
            return redirect()->back()->with('error', 'حدث خطأ في قاعدة البيانات، يرجى المحاولة لاحقًا.');
        } catch (\Exception $e) {
            Log::error('General Error', ['error' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
            return redirect()->back()->with('error', 'حدث خطأ غير متوقع. يرجى المحاولة لاحقًا.');
        }
    }


    public function success($id)
    {
        $concours = Concours::find($id);
        return view('concours.success', compact('concours'));
    }
    public function formprint($id)
    {
        $concours = Concours::find($id);

        if (!$concours) {
            return redirect()->route('concours.register')->with('error', 'السجل غير موجود');
        }

        $data = [
            'id' => $concours->id,
            'con_grade' => Concours::getGradeLabel($concours->con_grade),
            'specialty' => $concours->getSpecialty($concours->specialty),
            'username' => $concours->username,
            'password' => $concours->password,
            'NomArF' => $concours->NomArF,
            'PrenomArF' => $concours->PrenomArF,
            'birthNum' => $concours->birthNum,
            'DateNaiF' => $concours->DateNaiF,
            'gender' => $concours->gender,
            'familyStatus' => $concours->getFamilyStatus($concours->familyStatus),
            'childrenNumber' => $concours->childrenNumber,
            'LieuNaiArF' => $concours->LieuNaiArF,
            'personalAddress' => $concours->personalAddress,
            'residenceMunicipality' => $concours->getResidenceMunicipality($concours->residenceMunicipality),
            'phoneNumber' => $concours->phoneNumber,
            'diploma' => $concours->getDiploma($concours->diploma),
        ];

        return view('concours.Docprint', $data);
    }


    public function call()
    {
        return view('concours.istidea');
    }

    public function download(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        $user = Concours::where('username', $request->username)
            ->where('password', $request->password)
            ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'بيانات الدخول غير صحيحة.');
        }

        $data = [
            'NomArF' => $user->NomArF,
            'PrenomArF' => $user->PrenomArF,
            'residenceMunicipality' => $user->getResidenceMunicipality($user->residenceMunicipality),
            'con_grade' => Concours::getGradeLabel($user->con_grade),
            'personalAddress' => $user->personalAddress,
        ];

        // تحديد مجلد مؤقت للـ mPDF
        $tempDir = storage_path('app/mpdf_temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'tempDir' => $tempDir, // مهم جداً
        ]);

        // توليد الـ HTML من الـ Blade
        $html = view('concours.invitation', compact('data'))->render();
        $mpdf->WriteHTML($html);

        // لو تبغى العرض في المتصفح
        return response($mpdf->Output('استدعاء.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="استدعاء.pdf"');

        // ولو تبغى التحميل مباشرةً، استعمل:
        // return response($mpdf->Output('استدعاء.pdf', 'S'))
        //     ->header('Content-Type', 'application/pdf')
        //     ->header('Content-Disposition', 'attachment; filename="استدعاء.pdf"');
    }

    public function index()
    {
        return view('concours.trait');
    }


    public function filterUsers(Request $request)
    {
        $query = Concours::query();

        if ($request->filled('residenceMunicipality')) {
            $query->where('residenceMunicipality', $request->get('residenceMunicipality'));
        }

        if ($request->filled('con_grade')) {
            $query->where('con_grade', $request->get('con_grade'));
        }

        $users = $query->get();

        return response()->json(['users' => $users]);
    }
    public function getConcoursData(Request $request)
    {
        $id = $request->input('id');

        $concours = Concours::find($id);

        if (!$concours) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على بيانات المترشح'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $concours
        ]);
    }
    public function getDocuments(Request $request)
    {
        $userId = $request->input('id');
        $documents = Document::where('concours_id', $userId)->get();

        return response()->json([
            'success' => true,
            'data' => $documents
        ]);
    }

 public function updateDocumentsBulk(Request $request)
{
    $validated = $request->validate([
        'documents' => 'required|array',
        'documents.*' => 'in:مطابق,غير مطابق',
    ]);

    try {
        $documentIds = array_keys($validated['documents']);

        // 🧩 جلب الوثائق المطلوبة مع العلاقة
        $documents = Document::with('concours')
            ->whereIn('id', $documentIds)
            ->get();

        if ($documents->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على وثائق مطابقة للمعرفات المرسلة.'
            ], 404);
        }

        // ✅ تحديث حالة الوثائق دفعة واحدة
        foreach ($documents as $document) {
            $newStatus = $validated['documents'][$document->id] ?? null;
            if ($newStatus && $document->status !== $newStatus) {
                $document->status = $newStatus;
                $document->save();
            }
        }

        // ✅ تحديث حالة المترشحين بناءً على الوثائق
        $concoursIds = $documents->pluck('concours_id')->unique()->filter();
        $concoursList = Concours::with('documents')->whereIn('id', $concoursIds)->get();

        $updatedStatuses = [];

        foreach ($concoursList as $concours) {
            $concours->updateStatusBasedOnDocuments();
            $updatedStatuses[$concours->id] = $concours->status;
        }

        return response()->json([
            'success' => true,
            'message' => '✅ تم تحديث الوثائق والمترشحين بنجاح',
            'updated_statuses' => $updatedStatuses,
        ]);

    } catch (\Throwable $e) {
        \Log::error('خطأ أثناء تحديث الوثائق:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ غير متوقع أثناء تحديث الوثائق: ' . $e->getMessage(),
        ], 500);
    }
}


    public function stats()
    {
        $total = Concours::count();
        $accepted = Concours::where('status', 'مطابق')->count();
        $rejected = Concours::where('status', 'غير مطابق')->count();

        // إحصائيات حسب البلديات مع عدد المطابقة وغير المطابقة
        $byCommune = Concours::select(
            'residenceMunicipality',
            DB::raw('count(*) as total'),
            DB::raw("SUM(CASE WHEN status = 'مطابق' THEN 1 ELSE 0 END) as accepted"),
            DB::raw("SUM(CASE WHEN status = 'غير مطابق' THEN 1 ELSE 0 END) as rejected")
        )
            ->groupBy('residenceMunicipality')
            ->orderBy('total', 'desc')
            ->get();

        // إحصائيات حسب الرتب مع عدد المطابقة وغير المطابقة
        $byGrade = Concours::select(
            'con_grade',
            DB::raw('count(*) as total'),
            DB::raw("SUM(CASE WHEN status = 'مطابق' THEN 1 ELSE 0 END) as accepted"),
            DB::raw("SUM(CASE WHEN status = 'غير مطابق' THEN 1 ELSE 0 END) as rejected")
        )
            ->groupBy('con_grade')
            ->orderBy('total', 'desc')
            ->get();

        return view('concours.stats', compact('total', 'accepted', 'rejected', 'byCommune', 'byGrade'));
    }
}






