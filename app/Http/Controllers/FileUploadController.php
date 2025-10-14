<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use App\Models\RwMigration;
use App\Models\RwMigrationRndm;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\RwPavarImport;
use App\Imports\RwPaperImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\RwPaper;
use App\Models\RwPavar;
class FileUploadController extends Controller
{
    public function index(Request $request)
    {
        $years = RwMigration::where('YEAR', '>=', 2020)
            ->select('YEAR')
            ->distinct()
            ->orderBy('YEAR', 'desc')
            ->pluck('YEAR')
            ->toArray();

        if (empty($years)) {
            $years = [date('Y')];
        }

        $selectedYear = (int) ($request->input('year') ?: max($years));

        $migrations = RwMigration::where('YEAR', $selectedYear)
            ->orderBy('MONTH', 'desc')
            ->get();

        $yearsrndm = RwMigrationRndm::where('YEAR', '>=', 2020)
            ->select('YEAR')
            ->distinct()
            ->orderBy('YEAR', 'desc')
            ->pluck('YEAR')
            ->toArray();

        if (empty($yearsrndm)) {
            $yearsrndm = [date('Y')];
        }

        $selectedYearRndm = (int) ($request->input('yearrndm') ?: max($yearsrndm));

        $migrationsrndm = RwMigrationRndm::where('YEAR', $selectedYearRndm)
            ->orderBy('TRIMESTER', 'desc')
            ->get();

        return view('paie.index', compact(
            'migrations',
            'migrationsrndm',
            'years',
            'selectedYear',
            'yearsrndm',
            'selectedYearRndm',
        ));
    }
   public function processFile(Request $request)
{
    $request->validate([
        'LOT'   => 'required|string|regex:/^[a-zA-Z0-9_-]+$/',
        'month' => 'required|integer|min:1|max:12',
        'year'  => 'required|integer|min:2000|max:2100',
        'file'  => 'required|file|mimes:zip|max:20480', // 20MB
    ]);

    $fileName = $request->LOT . '_' . uniqid() . '.zip';

    DB::beginTransaction();
    try {
        // 🔍 تحقق إذا كان الشهر والسنة محجوزين
        $exists = RwMigration::where('MONTH', $request->month)
            ->where('YEAR', $request->year)
            ->exists();

        if ($exists) {
            return back()->with('error', 'هذا الشهر من هذه السنة محجوز بالفعل.');
        }

        // رفع الملف وتخزينه
        $storedPath = $request->file('file')->storeAs('uploads', $fileName, 'local');
        if (!$storedPath || !Storage::disk('local')->exists($storedPath)) {
            throw new \Exception("فشل في حفظ الملف.");
        }

        // إنشاء السجل
        $migration = RwMigration::create([
            'MONTH'  => $request->month,
            'YEAR'   => $request->year,
            'LOT'    => $request->LOT,
            'path'   => $storedPath,
            'STATUS' => 0,
        ]);

        if (!$migration) {
            Storage::disk('local')->delete($storedPath);
            throw new \Exception("فشل إدخال السجل في rw_migrations.");
        }

        DB::commit();
        return back()->with('success', 'تم رفع الملف بنجاح!');
    } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        // التحقق إذا كان الخطأ بسبب قيد الـ unique
        if ($e->getCode() == 23000) { // SQLSTATE[23000]: Integrity constraint violation
            return back()->with('error', 'هذا الشهر من هذه السنة محجوز بالفعل (من قاعدة البيانات).');
        }
        Log::error("خطأ SQL أثناء رفع الملف: " . $e->getMessage());
        return back()->with('error', 'حدث خطأ أثناء حفظ البيانات.');
    } catch (\Exception $e) {
        DB::rollBack();
        // إذا الملف مرفوع وحصل خطأ → نحذفه
        if (Storage::disk('local')->exists('uploads/' . $fileName)) {
            Storage::disk('local')->delete('uploads/' . $fileName);
        }
        Log::error("خطأ أثناء رفع الملف: " . $e->getMessage());
        return back()->with('error', $e->getMessage());
    }
}



    public function execute(Request $request)
    {
        try {
            ini_set('max_execution_time', 1200);

            $migrationId = $request->input('migration_id');
            $migration = RwMigration::findOrFail($migrationId);

            $fileFullPath = Storage::path($migration->path);
            $extractDir = 'extracted/' . $migration->LOT;
            Storage::makeDirectory($extractDir);
            $extractPath = Storage::path($extractDir);

            $zip = new ZipArchive;
            if ($zip->open($fileFullPath) !== true) {
                return back()->with('error', 'فشل في فتح ملف ZIP.')->with('activeTab', '#salartab');
            }

            $fileNames = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileNames[] = $zip->getNameIndex($i);
            }
            Log::info("ملفات داخل ZIP:", $fileNames);

            $zip->extractTo($extractPath);
            $zip->close();

            $allFiles = collect(Storage::files($extractDir));
            $papersFiles = $allFiles->filter(fn($f) => preg_match('/PAPERS.*\.(csv|xlsx)$/i', basename($f)));
            $pavarFiles = $allFiles->filter(fn($f) => preg_match('/PAVAR.*\.(csv|xlsx)$/i', basename($f)));

            if ($papersFiles->isEmpty() || $pavarFiles->isEmpty()) {
                $migration->update(['STATUS' => -1]);
                return back()->with('error', 'لم يتم العثور على الملفات المطلوبة.')->with('activeTab', '#salartab');
            }

            foreach ($papersFiles as $file) {
                $this->importData(Storage::path($file), RwPaperImport::class, $migrationId);
            }
            foreach ($pavarFiles as $file) {
                $this->importData(Storage::path($file), RwPavarImport::class, $migrationId);
            }

            $migration->update(['STATUS' => 1]);
            return back()->with('success', 'تم تنفيذ العملية بنجاح.')->with('activeTab', '#salartab');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'السجل غير موجود.')->with('activeTab', '#salartab');
        } catch (\Exception $e) {
            Log::error('خطأ أثناء التنفيذ: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء التنفيذ: ' . $e->getMessage())->with('activeTab', '#salartab');
        }
    }

    public function importData($filePath, $importClass, $migrationId)
    {
        try {
            Log::info("بدأ استيراد البيانات من الملف: " . $filePath);
            Excel::import(new $importClass($migrationId), $filePath);
            Log::info('تم استيراد البيانات بنجاح من الملف: ' . $filePath);
        } catch (\Exception $e) {
            Log::error('فشل في استيراد البيانات من الملف: ' . $filePath . ' - ' . $e->getMessage());
        }
    }
    public function deleteFile(Request $request)
    {
        $request->validate([
            'migration_id' => 'required|exists:rw_migrations,ID_MIGRATION',
        ]);
        try {
            DB::transaction(function () use ($request) {
                $migration = RwMigration::where('ID_MIGRATION', $request->migration_id)->firstOrFail();
                RwPavar::where('ID_MIGRATION', $migration->ID_MIGRATION)->delete();
                RwPaper::where('ID_MIGRATION', $migration->ID_MIGRATION)->delete();
                $migration->delete();
            });
            return redirect()->back()->with('success', 'تم حذف الملف بنجاح.');
        } catch (\Exception $e) {
            \Log::error('خطأ في حذف الملف: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }
    public function showSalaryReport(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $migration = RwMigration::where('MONTH', $month)->where('YEAR', $year)->first();

        if (!$migration) {
            return back()->with('error', 'لم يتم العثور على سجلات لهذا الشهر والسنة.');
        }
        $salaryReport = DB::table('rw_pavar')
            ->join('rw_papers', 'rw_papers.MATRI', '=', 'rw_pavar.MATRI')
            ->select('rw_papers.MATRI as EmployeeID', 'rw_papers.Name as EmployeeName', 'rw_pavar.MONTANT', 'rw_pavar.MFIX', 'rw_pavar.TAUX')
            ->where('rw_pavar.ID_MIGRATION', $migration->id)
            ->get();

        return view('paie.salary_report', compact('salaryReport', 'month', 'year'));
    }


}
