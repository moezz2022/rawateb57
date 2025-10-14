<?php

use App\Http\Controllers\RndmController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\FileUploadRndmController;
use App\Http\Controllers\PrimeRendementController;
use App\Http\Controllers\RendementSettingController;
use App\Http\Controllers\PrimeScolariteController;
use App\Http\Controllers\MonthlyAbsenceController;
use App\Http\Controllers\AtsController;
use App\Http\Controllers\ConcoursController;

Route::middleware('auth')->get('/messages/latest', [MessageController::class, 'latest']);


Route::get('/', action: function () {
    return view('auth.index');
})->name('auth.index');

Route::get('/login', action: function () {
    return view('auth.login');
});
Route::get('/two-factor-challenge', [TwoFactorController::class, 'showTwoFactorForm'])->name('auth.twofactor-challenge');
Route::post('/two-factor-challenge', [TwoFactorController::class, 'verifyTwoFactor'])->name('auth.twofactor-challenge.verify');
Route::post('/logout-twofactor', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return response()->json(['message' => 'تم حذف الجلسة بنجاح']);
});
/********************************************************************************************************************/

Route::get('/get-communes/{daira_id}', [ConcoursController::class, 'getCommunes']);
Route::get('/concours', [ConcoursController::class, 'reg'])->name('concours.register');
Route::get('/concours/istidea', [ConcoursController::class, 'call'])->name('concours.istidea');
Route::get('/istidea/print/{id}', [ConcoursController::class, 'formprint'])->name('concours.Docprint');
Route::post('/candidate/reg', [ConcoursController::class, 'store'])->name('concours.store');
Route::post('/istidea/download', [ConcoursController::class, 'download'])->name('istidea.download');
Route::get('/concours/trait', [ConcoursController::class, 'index'])->name('concours.trait');
Route::get('/concours/stats', [ConcoursController::class, 'stats'])->name('concours.stats');
Route::get('/filter-users', [ConcoursController::class, 'filterUsers'])->name('filter.users');
Route::get('/concours/data', [ConcoursController::class, 'getConcoursData'])->name('getConcoursData');
Route::get('/getDocuments', [ConcoursController::class, 'getDocuments'])->name('getDocuments');
Route::post('/documents/update-bulk', [ConcoursController::class, 'updateDocumentsBulk'])
    ->name('updateDocumentsBulk');
Route::get('/concours/{id}', [ConcoursController::class, 'success'])->name('concours.success');



Route::middleware(['auth', 'twofactor', 'admin'])->group(function () {


    Route::get('/users/activate', [AdminController::class, 'index'])->name('users.activeuser.index');
    Route::post('/users/activate/{id}', [AdminController::class, 'activateUser'])->name('users.activateuser');
    Route::post('/users/deactivate/{id}', [AdminController::class, 'deactivateUser'])->name('users.deactivate');
    Route::get('/users/{id}/edit', [AdminController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{id}', [AdminController::class, 'update'])->name('users.update');

    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.add');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{id}/edit', [GroupController::class, 'edit'])->name('groups.edit');
    Route::patch('/groups/{id}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/groups/{id}', [GroupController::class, 'destroy'])->name('groups.delete');
    Route::post('/groups/import', [GroupController::class, 'import'])->name('groups.import');
    Route::get('/sub-groups', action: [GroupController::class, 'getSubGroups'])->name('subgroups.get');

    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.add');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::post('/employees/import', [EmployeeController::class, 'import'])->name('employees.import');
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::get('/employees/statistics', [EmployeeController::class, 'statistics'])->name('employees.statistics');

    Route::get('/cards/select-grade', [EmployeeController::class, 'selectGradeForCards'])->name('cards.select.grade');
    Route::get('/cards/load-employees', [EmployeeController::class, 'loadEmployeesByGrade'])->name('cards.load.employees');
    Route::match(['get', 'post'], '/cards/print-selected', [EmployeeController::class, 'printSelectedEmployees'])
        ->name('cards.print.selected');
    Route::get('/cards/back', [EmployeeController::class, 'showBack'])->name('employees.cardsback');



    Route::get('/paie/folioupload', [FileUploadController::class, 'index'])->name('paie.index');
    Route::post('/paie/upload', [FileUploadController::class, 'processFile'])->name('upload.process');
    Route::post('/execute', [FileUploadController::class, 'execute'])->name('execute.file');
    Route::post('/delete-file', [FileUploadController::class, 'deleteFile'])->name('delete.file');
    Route::get('/paie/search', [PayrollController::class, 'search'])->name('paie.search');
    Route::get('/paie/salary-slip/{matri}/{month}/{year}', [PayrollController::class, 'showSalarySlip']);
    Route::get('/paie', [PayrollController::class, 'show'])->name('paie.show');

    Route::post('/paie/upload_rndm', action: [FileUploadRndmController::class, 'processFile_rndm'])->name('upload.process_rndm');
    Route::post('/execute_rndm', [FileUploadRndmController::class, 'execute_rndm'])->name('execute.file_rndm');
    Route::post('/delete-file_rndm', [FileUploadRndmController::class, 'deleteFile_rndm'])->name('delete.file_rndm');


    // الحجز وحفظ المردودية
    Route::prefix('prime-rendements')->name('prime_rendements.')->group(function () {
        Route::post('/store', [PrimeRendementController::class, 'store'])->name('store');
        Route::get('/current-period', [PrimeRendementController::class, 'getCurrentPeriod'])->name('currentPeriod');
        Route::get('/details/{year}/{quarter}', [PrimeRendementController::class, 'details'])->name('details');
        Route::get('/export/{year}/{quarter}', [PrimeRendementController::class, 'exportUpdatesSql'])->name('export.sql');
    });

    // إعدادات الفتح/الغلق للثلاثيات
    Route::prefix('prime-rendements/settings')->name('prime_rendements.settings.')->group(function () {
        Route::get('/', [RendementSettingController::class, 'months'])->name('months');
        Route::post('/', [RendementSettingController::class, 'store'])->name('store');
        Route::patch('/{id}/toggle', [RendementSettingController::class, 'toggle'])->name('toggle');

    });

    Route::prefix('/absences')->name('monthly_absences.')->group(function () {
        Route::get('months', [MonthlyAbsenceController::class, 'months'])->name('months');
        Route::post('months/store', [MonthlyAbsenceController::class, 'storeSetting'])->name('storeSetting');
        Route::patch('months/{id}/toggle', [MonthlyAbsenceController::class, 'toggle'])->name('toggle');
        Route::delete('months/clear', [MonthlyAbsenceController::class, 'clear'])->name('clear');
        Route::get('/', [MonthlyAbsenceController::class, 'index'])->name('index');
        Route::post('store', [MonthlyAbsenceController::class, 'store'])->name('store');
        Route::get('export/{year}/{month}', [MonthlyAbsenceController::class, 'export'])->name('export');
        Route::get('details/{year}/{month}', [MonthlyAbsenceController::class, 'details'])
            ->whereNumber(['year', 'month'])
            ->name('details');
    });

    Route::prefix('/primescolarite')->name('prime_scolarité.')->group(function () {
        Route::get('/primesettings', [PrimeScolariteController::class, 'primesettings'])->name('primesettings');
        Route::post('/primesettings/store', [PrimeScolariteController::class, 'storeSetting'])->name('storeSetting');
        Route::patch('/primesettings/{id}/toggle', [PrimeScolariteController::class, 'toggle'])->name('toggle');
        Route::get('/', [PrimeScolariteController::class, 'index'])->name('index');
        Route::post('/store', [PrimeScolariteController::class, 'store'])->name('store');
        Route::get('/export/{year}', [PrimeScolariteController::class, 'export'])->name('export');

    });


});



Route::middleware(['auth', 'twofactor'])->group(function () {


    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/edit', [ProfileController::class, 'update'])->name('profile.updateprofile');
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.editpassword');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatepassword');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.updateavatar');

    // مسارات المصادقة الثنائية
    Route::get('/twofactorchallenge', [TwoFactorController::class, 'create'])->name('auth.twofactorchallenge');
    Route::post('/two-factor-authentication/enable', [TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::post('/two-factor-authentication/disable', [TwoFactorController::class, 'disable'])->name('two-factor.disable');

    Route::get('/dashboard', [MessageController::class, 'index'])->name('dashboard');
    Route::get('/inbox', [MessageController::class, 'inbox'])->name('messages.inbox');
    Route::get('/outbox', [MessageController::class, 'outbox'])->name('messages.outbox');
    Route::get('/new-message', [MessageController::class, 'create'])->name('messages.create');
    Route::get('/messages/success', function () {
        return view('messages.success');
    })->name('messages.success');
    Route::get('/messages/search', [MessageController::class, 'search'])->name('messages.search');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/save', [MessageController::class, 'save'])->name('messages.save');
    Route::get('/messages/save', [MessageController::class, 'savedMessages'])->name('messages.saved');
    Route::post('/messages/restore-saved', [MessageController::class, 'restoreSaved'])->name('messages.restoreSaved');
    Route::delete('/messages/delete', [MessageController::class, 'delete'])->name('messages.delete');
    Route::get('messages/trash', [MessageController::class, 'trash'])->name('messages.trash');
    Route::post('/messages/manage', [MessageController::class, 'manage'])->name('messages.manage');
    Route::post('/messages/restore', [MessageController::class, 'restore'])->name('messages.restore');
    Route::delete('/messages/permanently-delete', [MessageController::class, 'permanentlyDelete'])->name('messages.permanentlyDelete');
    Route::get('/messages/{id}/forward', [MessageController::class, 'forward'])->name('messages.forward');
    Route::post('/messages/forward/{id}', [MessageController::class, 'forwardStore'])->name('messages.forwardStore');
    Route::get('attachments/download/{filename}', [AttachmentController::class, 'download'])->name('attachments.download');
    Route::post('/upload', [FileController::class, 'upload'])->name('upload');
    Route::get('/messages/{slug}', [MessageController::class, 'show'])->name('messages.show');



    Route::get('/users', [AdminController::class, 'show'])->name(name: 'users.indexuser');

    Route::post('/employees/{id}/assign', [AdminController::class, 'assign'])->name('employees.assign');
    Route::post('/employees/{id}/unassign', [AdminController::class, 'unassign'])->name('employees.unassign');
    Route::get('/get-sub-groups/{id}', [AdminController::class, 'getSubGroups']);
    Route::get('/transfer', [AdminController::class, 'showTransferPage'])->name('users.transfer');
    Route::post('/users/update-group', [AdminController::class, 'updateGroup'])->name('users.updateGroup');
    Route::post('/update-employee-group', [AdminController::class, 'updateGroup'])->name('employee.updateGroup');
    Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');

    Route::get('/paie/search', [PayrollController::class, 'search'])->name('paie.search');
    Route::get('/paie', [PayrollController::class, 'show'])->name('paie.show');
    Route::get('/employees/search', [EmployeeController::class, 'search'])->name('employees.search');
    Route::get('/paie/salary-slip/{matri}/{month}/{year}', [PayrollController::class, 'showSalarySlip']);

    Route::get('/paie/searchannual', [PayrollController::class, 'searchannual'])->name('paie.searchannual');
    Route::get('/payannual', [PayrollController::class, 'pay_show'])->name('paie.salaryannualshow');

    Route::get('/paie/salary-annual/{matri}/{year}', [PayrollController::class, 'showSalaryannual']);

    Route::get('/paie/salary_details', [PayrollController::class, 'showdetails'])->name('paie.salary_details');
    Route::get('/paie/details/{month}/{year}/{adm}', [PayrollController::class, 'salaryDetails'])->name('paie.details');

    Route::get('/paie/rndm_details', [RndmController::class, 'showrndmdetails'])->name('paie.rndm_details');
    Route::get('/paie/rndmdetails/{trimester}/{year}/{adm}', [RndmController::class, 'rndmDetails'])->name('paie.rndmdetails');

    Route::get('/paie/salary-report', [PayrollController::class, 'show_report'])->name('paie.salaryreport');
    Route::get('/paie/search-report', [PayrollController::class, 'SearchReport'])->name('paie.SearchReport');
    Route::get('/paie/details-report/{matri}/{year}/{start_month}/{end_month}', [PayrollController::class, 'showDetailedSalaryRange']);


    Route::prefix('prime-rendements')->name('prime_rendements.')->group(function () {
        Route::get('/rndmsettings', [RendementSettingController::class, 'rndmsettings'])->name('rndmsettings');
        Route::get('/create', [PrimeRendementController::class, 'create'])->name('create');
        Route::post('/store', [PrimeRendementController::class, 'store'])->name('store');
        Route::post('/reset', [PrimeRendementController::class, 'reset'])->name('reset');
        Route::get('/show', [PrimeRendementController::class, 'show'])->name('show');

    });

    Route::prefix('/absences')->name('monthly_absences.')->group(function () {

        Route::get('settings', [MonthlyAbsenceController::class, 'settings'])->name('settings');
        Route::get('create', [MonthlyAbsenceController::class, 'create'])->name('create'); // إضافة
        Route::post('store', [MonthlyAbsenceController::class, 'store'])->name('store'); // حفظ
        Route::get('details/{year}/{month}', [MonthlyAbsenceController::class, 'details'])
            ->whereNumber(['year', 'month'])
            ->name('details');
    });

    Route::prefix('/ats')->name('ats.')->group(function () {
        Route::get('settings', [AtsController::class, 'index'])->name('settings');
        Route::get('months/{year}', [AtsController::class, 'getMonths'])->name('months');
        Route::get('employees/{year}/{month}', [AtsController::class, 'getEmployees'])->name('employees');
        Route::get('page1/{matricule}', [AtsController::class, 'ats1'])->name('generate1');
        Route::get('page2/{matricule}', [AtsController::class, 'ats2'])->name('generate2');
        Route::get('page3/{matricule}', [AtsController::class, 'ats3'])->name('generate3');
    });


    Route::prefix('/primescolarite')->name('prime_scolarité.')->group(function () {

        Route::get('/settings', [PrimeScolariteController::class, 'settings'])->name('settings');
        Route::get('/create', [PrimeScolariteController::class, 'create'])->name('create'); // إضافة
        Route::post('/store', [PrimeScolariteController::class, 'store'])->name('store'); // حفظ
        Route::get('/show/{year}', [PrimeScolariteController::class, 'show'])->name('show');
    });

});



require __DIR__ . '/auth.php';
