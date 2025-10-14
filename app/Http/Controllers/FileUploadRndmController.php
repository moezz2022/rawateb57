<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use App\Models\RwMigrationRndm;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\RwPaperRndmImport;
use App\Imports\RwPavarRndmImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\PavarRndm;
use App\Models\PaperRndm;
class FileUploadRndmController extends Controller
{

    public function processFile_rndm(Request $request)
    {
        $request->validate([
            'TITLE' => 'required|string|max:255',
            'LOT' => 'required|string|regex:/^[a-zA-Z0-9_-]+$/',
            'TRIMESTER' => 'required|string',
            'YEAR' => 'required|integer|min:2000|max:2100',
            'file' => 'required|file|mimes:zip|max:20480',
        ]);

        $fileName = $request->LOT . '_' . uniqid() . '.zip';
        $filePath = 'uploads/' . $fileName;

        DB::beginTransaction();
        try {
            $stored = Storage::putFileAs('uploads', $request->file('file'), $fileName);

            if (!$stored || !Storage::exists($filePath)) {
                throw new \Exception("فشل في حفظ الملف.");
            }
            $migrationrndm = RwMigrationRndm::create([
                'TRIMESTER' => $request->TRIMESTER,
                'YEAR' => $request->YEAR,
                'LOT' => $request->LOT,
                'TITLE' => $request->TITLE,
                'path' => $filePath,
                'STATUS' => 0,
            ]);

            if (!$migrationrndm) {
                throw new \Exception("فشل إدخال السجل في `rw_migrations_rndm`.");
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'تم رفع الملف المردودية بنجاح!')
                ->with('activeTab', '#rndmTab');

        } catch (\Exception $e) {
            DB::rollBack();

            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            Log::error("خطأ أثناء رفع الملف: " . $e->getMessage());

            return back()
                ->with('error', 'حدث خطأ أثناء رفع الملف.')
                ->with('activeTab', '#rndmTab');
        }
    }

    public function execute_rndm(Request $request)
    {
        try {
            ini_set('max_execution_time', 1200);

            $migrationId = $request->input('migration_id');
            $migration = RwMigrationRndm::findOrFail($migrationId);

            $fileFullPath = Storage::path($migration->path);
            $extractDir = 'extracted/' . $migration->LOT;
            Storage::makeDirectory($extractDir);
            $extractPath = Storage::path($extractDir);

            $zip = new ZipArchive;
            if ($zip->open($fileFullPath) !== true) {
                return back()->with('error', 'فشل في فتح ملف ZIP.')->with('activeTab', '#rndmTab');
            }

            $fileNames = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileNames[] = $zip->getNameIndex($i);
            }
            Log::info("ملفات داخل ZIP:", $fileNames);

            $zip->extractTo($extractPath);
            $zip->close();

            $allFiles = collect(Storage::files($extractDir));
            $papersFiles = $allFiles->filter(fn($f) => preg_match('/PRPERS.*\.(csv|xlsx)$/i', basename($f)));
            $pavarFiles = $allFiles->filter(fn($f) => preg_match('/PRVAR.*\.(csv|xlsx)$/i', basename($f)));

            if ($papersFiles->isEmpty() || $pavarFiles->isEmpty()) {
                $migration->update(['STATUS' => -1]);
                return back()->with('error', 'لم يتم العثور على الملفات المطلوبة.')->with('activeTab', '#rndmTab');
            }

            foreach ($papersFiles as $file) {
                $this->importData(Storage::path($file), RwPaperRndmImport::class, $migrationId);
            }
            foreach ($pavarFiles as $file) {
                $this->importData(Storage::path($file), RwPavarRndmImport::class, $migrationId);
            }

            $migration->update(['STATUS' => 1]);
            return back()->with('success', 'تم تنفيذ العملية بنجاح.')->with('activeTab', '#rndmTab');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'السجل غير موجود.')->with('activeTab', '#rndmTab');
        } catch (\Exception $e) {
            Log::error('خطأ أثناء التنفيذ: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء التنفيذ: ' . $e->getMessage())->with('activeTab', '#rndmTab');
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
    public function deleteFile_rndm(Request $request)
    {
        $request->validate([
            'migration_id' => 'required|exists:rw_migrations_rndm,ID_MIGRATION',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $migration = RwMigrationRndm::where('ID_MIGRATION', $request->migration_id)->firstOrFail();

                if ($migration->path && Storage::exists($migration->path)) {
                    Storage::delete($migration->path);
                }

                $extractPath = 'extracted/' . $migration->LOT;
                if (Storage::exists($extractPath)) {
                    Storage::deleteDirectory($extractPath);
                }

                PavarRndm::where('ID_MIGRATION', $migration->ID_MIGRATION)->delete();
                PaperRndm::where('ID_MIGRATION', $migration->ID_MIGRATION)->delete();

                $migration->delete();
            });

            return redirect()
                ->back()
                ->with('success', 'تم حذف الملف بنجاح.')
                ->with('activeTab', '#rndmTab');

        } catch (\Exception $e) {
            \Log::error('خطأ في حذف الملف: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage())
                ->with('activeTab', '#rndmTab');
        }
    }



}
