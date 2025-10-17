<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * عرض سجلات الحضور (صفحة مفهرسة)
     * Route: /attendance/records
     */
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $search = $request->input('search');
        $status = $request->input('status');
        
        $query = Attendance::with('employee')
            ->where('date', $date);
        
        // فلترة حسب البحث
        if ($search) {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('NOMA', 'like', "%{$search}%")
                  ->orWhere('PRENOMA', 'like', "%{$search}%")
                  ->orWhere('MATRI', 'like', "%{$search}%");
            });
        }
        
        // فلترة حسب الحالة
        if ($status) {
            $query->where('status', $status);
        }
        
        $attendances = $query->orderBy('check_in', 'desc')
            ->paginate(50);
        
        // إحصائيات اليوم
        $statistics = [
            'total' => Attendance::where('date', $date)->count(),
            'present' => Attendance::where('date', $date)->where('status', 'present')->count(),
            'late' => Attendance::where('date', $date)->where('status', 'late')->count(),
            'absent' => Attendance::where('date', $date)->where('status', 'absent')->count(),
        ];
        
        return view('attendance.records', compact('attendances', 'date', 'statistics'));
    }

    /**
     * عرض صفحة المسح بالكاميرا
     * Route: /attendance/scan-camera
     */
    public function scanCameraPage()
    {
        return view('attendance.scan_camera');
    }

    /**
     * عرض صفحة المسح بالباركود
     * Route: /attendance/scan-barcode
     */
    public function scanBarcodePage()
    {
        return view('attendance.scan_barcode');
    }

    /**
     * API للمسح (JSON) - يدعم الباركود والكاميرا
     * Route: POST /attendance/api/scan
     */
    public function apiScan(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string|min:1|max:100'
            ]);

            $code = trim($request->input('code'));

            $employee = Employee::where('barcode', $code)
                         ->orWhere('MATRI', $code)
                         ->first();

            if (!$employee) {
                Log::warning('Barcode scan failed: Employee not found', [
                    'code' => $code,
                    'ip' => $request->ip()
                ]);
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'الموظف غير موجود - الكود: ' . $code
                ], 404);
            }

            $today = Carbon::today()->toDateString();
            $nowTime = Carbon::now()->format('H:i:s');
            $nowDateTime = Carbon::now();

            DB::beginTransaction();

            try {
                $attendance = Attendance::firstOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'date' => $today
                    ],
                    [
                        'check_in' => null,
                        'check_out' => null,
                        'status' => 'absent',
                        'device' => $this->getDeviceInfo($request)
                    ]
                );

                $type = '';
                $message = '';

                if (!$attendance->check_in) {
                    $attendance->update([
                        'check_in' => $nowTime,
                        'status' => $this->determineStatus($nowDateTime),
                        'device' => $this->getDeviceInfo($request)
                    ]);
                    
                    $type = 'check_in';
                    $message = 'تم تسجيل الدخول بنجاح';
                    
                    Log::info('Check-in recorded', [
                        'employee_id' => $employee->id,
                        'time' => $nowTime
                    ]);
                }
                elseif ($attendance->check_in && !$attendance->check_out) {
                    $checkInTime = Carbon::parse($today . ' ' . $attendance->check_in);
                    $minDuration = 30;
                    
                    if ($nowDateTime->diffInMinutes($checkInTime) < $minDuration) {
                        DB::rollBack();
                        return response()->json([
                            'status' => 'error',
                            'message' => "يجب الانتظار {$minDuration} دقيقة على الأقل قبل تسجيل الخروج"
                        ], 422);
                    }

                    $attendance->update([
                        'check_out' => $nowTime,
                        'device' => $this->getDeviceInfo($request)
                    ]);
                    
                    $type = 'check_out';
                    $message = 'تم تسجيل الخروج بنجاح';
                }
                else {
                    $attendance->update([
                        'check_out' => $nowTime,
                        'device' => $this->getDeviceInfo($request)
                    ]);
                    
                    $type = 'check_out_updated';
                    $message = 'تم تحديث وقت الخروج';
                }

                DB::commit();

                $workHours = null;
                if ($attendance->check_out) {
                    $workHours = $this->calculateWorkHours($attendance->check_in, $attendance->check_out);
                }

                return response()->json([
                    'status' => 'success',
                    'type' => $type,
                    'message' => $message,
                    'time' => $nowTime,
                    'date' => $today,
                    'employee' => [
                        'id' => $employee->id,
                        'NOMA' => $employee->NOMA,
                        'PRENOMA' => $employee->PRENOMA,
                        'MATRI' => $employee->MATRI,
                        'barcode' => $employee->barcode
                    ],
                    'attendance' => [
                        'check_in' => $attendance->check_in,
                        'check_out' => $attendance->check_out,
                        'status' => $attendance->status,
                        'work_hours' => $workHours
                    ]
                ], 200);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Attendance scan error', [
                'message' => $e->getMessage(),
                'code' => $request->input('code'),
                'ip' => $request->ip()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء المعالجة'
            ], 500);
        }
    }

    /**
     * معالجة المسح عبر POST (للباركود الفعلي)
     * Route: POST /attendance/scan
     */
    public function postScan(Request $request)
    {
        $result = $this->apiScan($request);
        $data = $result->getData(true);

        if ($data['status'] === 'success') {
            return redirect()->back()
                ->with('success', $data['message'])
                ->with('employee', $data['employee'])
                ->with('time', $data['time']);
        } else {
            return redirect()->back()
                ->with('error', $data['message']);
        }
    }

    /**
     * عرض تفاصيل سجل حضور
     * Route: /attendance/records/{id}
     */
    public function show($id)
    {
        $attendance = Attendance::with('employee')->findOrFail($id);
        return view('attendance.show', compact('attendance'));
    }

    /**
     * سجلات حضور موظف محدد
     * Route: /attendance/employee/{employeeId}
     */
    public function employeeRecords($employeeId, Request $request)
    {
        $employee = Employee::findOrFail($employeeId);
        
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        
        $attendances = Attendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->paginate(30);
        
        return view('attendance.employee_records', compact('employee', 'attendances', 'startDate', 'endDate'));
    }

    /**
     * صفحة التقارير
     * Route: /attendance/reports
     */
    public function reports(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        
        $statistics = [
            'total_days' => Carbon::parse($startDate)->diffInDays($endDate) + 1,
            'total_employees' => Employee::count(),
            'total_attendances' => Attendance::whereBetween('date', [$startDate, $endDate])->count(),
            'present_count' => Attendance::whereBetween('date', [$startDate, $endDate])
                ->where('status', 'present')->count(),
            'late_count' => Attendance::whereBetween('date', [$startDate, $endDate])
                ->where('status', 'late')->count(),
            'absent_count' => Attendance::whereBetween('date', [$startDate, $endDate])
                ->where('status', 'absent')->count(),
        ];
        
        return view('attendance.reports', compact('statistics', 'startDate', 'endDate'));
    }

    /**
     * تصدير البيانات
     * Route: /attendance/export
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        $format = $request->input('format', 'json'); // json, csv, excel
        
        $attendances = Attendance::with('employee')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();
        
        if ($format === 'csv') {
            return $this->exportCsv($attendances);
        }
        
        // Default: JSON
        return response()->json([
            'data' => $attendances,
            'meta' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'count' => $attendances->count()
            ]
        ]);
    }

    /**
     * تصدير CSV
     */
    private function exportCsv($attendances)
    {
        $filename = 'attendance_' . Carbon::now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($attendances) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, ['التاريخ', 'الموظف', 'رقم التسجيل', 'وقت الدخول', 'وقت الخروج', 'الحالة', 'ساعات العمل']);
            
            foreach ($attendances as $att) {
                fputcsv($file, [
                    $att->date,
                    ($att->employee->NOMA ?? '') . ' ' . ($att->employee->PRENOMA ?? ''),
                    $att->employee->MATRI ?? '',
                    $att->check_in,
                    $att->check_out,
                    $att->status,
                    $this->calculateWorkHours($att->check_in, $att->check_out)
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * تحديد حالة الحضور
     */
    private function determineStatus(Carbon $checkInTime): string
    {
        $workStartTime = Carbon::createFromTime(8, 0, 0);
        $lateThreshold = Carbon::createFromTime(8, 15, 0);
        
        $checkInTimeOnly = Carbon::createFromTime(
            $checkInTime->hour,
            $checkInTime->minute,
            $checkInTime->second
        );

        if ($checkInTimeOnly->lte($workStartTime)) {
            return 'present';
        } elseif ($checkInTimeOnly->lte($lateThreshold)) {
            return 'present';
        } else {
            return 'late';
        }
    }

    /**
     * حساب ساعات العمل
     */
    private function calculateWorkHours(?string $checkIn, ?string $checkOut): ?string
    {
        if (!$checkIn || !$checkOut) {
            return null;
        }

        try {
            $start = Carbon::createFromFormat('H:i:s', $checkIn);
            $end = Carbon::createFromFormat('H:i:s', $checkOut);
            
            $diff = $start->diff($end);
            
            return sprintf('%02d:%02d:%02d', 
                $diff->h + ($diff->days * 24), 
                $diff->i, 
                $diff->s
            );
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * معلومات الجهاز
     */
    private function getDeviceInfo(Request $request): string
    {
        $userAgent = $request->userAgent();
        $ip = $request->ip();
        
        if (preg_match('/mobile|android|iphone/i', $userAgent)) {
            $device = 'Mobile';
        } elseif (preg_match('/tablet|ipad/i', $userAgent)) {
            $device = 'Tablet';
        } else {
            $device = 'Desktop';
        }
        
        return "{$device} - {$ip}";
    }
}