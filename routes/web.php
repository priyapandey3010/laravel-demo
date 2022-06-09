<?php

use Illuminate\Support\Facades\Route;
use App\Modules\CaseType\CaseTypeController;
use App\Modules\Bench\BenchController;
use App\Modules\Category\CategoryController;
use App\Modules\Status\StatusController;
use App\Modules\Court\CourtController;
use App\Modules\Cases\CaseController;
use App\Modules\ManageCaseMaster\ManageCaseMasterController;
use App\Modules\User\UserController;
use App\Modules\AuditTrail\AuditTrailController;
use App\Modules\UserPermission\UserPermissionController;
use App\Modules\Role\RoleController;
use App\Modules\Permission\PermissionController;
use App\Modules\PermissionGroup\PermissionGroupController;
use App\Modules\RolePermission\RolePermissionController;
use App\Modules\Designation\DesignationController;
use App\Modules\Department\DepartmentController;
use App\Modules\Dashboard\DashboardController;
use App\Modules\DisplayBoardUploads\DisplayBoardUploadsController;
use App\Modules\DisplayBoard\DisplayBoardController;
use App\Http\Controllers\PrivateController;
use App\Http\Controllers\ProfileController;
use App\Modules\UserDisplayBoard\UserDisplayBoardController;
use App\Modules\CaseJudgeApis\CaseJudgeApisController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [UserDisplayBoardController::class, 'index']);
Route::get('user-display-board/get-list', [UserDisplayBoardController::class, 'getList']);
Route::get('user-display-board/get-court-cases/{id}', [UserDisplayBoardController::class, 'getCourtCases']);
Route::post('user-display-board/set-bench', [UserDisplayBoardController::class, 'setBench']);
Route::get('details/{id}', [UserDisplayBoardController::class, 'details']);

Route::get('cases', [CaseJudgeApisController::class, 'index']);
Route::post('cases_details', [CaseJudgeApisController::class, 'cases_details']);
Route::post('view_details', [CaseJudgeApisController::class, 'case_view_details']);
Route::get('judge', [CaseJudgeApisController::class, 'judge_filters']);

Route::middleware(['xss', 'auth'])->group(function() {

    Route::get('/case-types/datalist', [CaseTypeController::class, 'datalist']);
    Route::resource('case-types', CaseTypeController::class);

    Route::get('/bench/datalist', [BenchController::class, 'datalist']);
    Route::resource('bench', BenchController::class);

    Route::get('/category/datalist', [CategoryController::class, 'datalist']);
    Route::resource('category', CategoryController::class);

    Route::get('/status/datalist', [StatusController::class, 'datalist']);
    Route::resource('status', StatusController::class);

    Route::get('/courts/datalist', [CourtController::class, 'datalist']);
    Route::resource('courts', CourtController::class);

    Route::get('/case/datalist', [ManageCaseMasterController::class, 'datalist']);
    Route::get('/case/get-courts/{id}', [ManageCaseMasterController::class, 'getCourts']);
    Route::post('/case/reorder', [CaseController::class, 'reorder']);
    Route::resource('case', ManageCaseMasterController::class);

    Route::get('/users/datalist', [UserController::class, 'datalist']);
    Route::post('/users/{id}/update-password', [UserController::class, 'updatePassword']);
    Route::post('/users/{id}/update-photo', [UserController::class, 'updatePhoto']);
    Route::post('/users/activate/{id}', [UserController::class, 'activate']);
    Route::post('/users/deactivate/{id}', [UserController::class, 'deactivate']);
    Route::resource('users', UserController::class);

    Route::get('user-permissions/{id}', [UserPermissionController::class, 'show']);
    Route::post('user-permissions', [UserPermissionController::class, 'store']);

    Route::post('/profile/{id}/update-password', [ProfileController::class, 'changePassword']);
    Route::post('/profile/{id}/update-photo', [ProfileController::class, 'updatePhoto']);
    Route::resource('profile', ProfileController::class);

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/display-board-uploads/datalist', [DisplayBoardUploadsController::class, 'datalist']);
    Route::post('/display-board-uploads/upload', [DisplayBoardUploadsController::class, 'upload']);
    Route::resource('display-board-uploads', DisplayBoardUploadsController::class);

    Route::get('/roles/datalist', [RoleController::class, 'datalist']);
    Route::resource('roles', RoleController::class);

    Route::get('/permissions/datalist', [PermissionController::class, 'datalist']);
    Route::resource('permissions', PermissionController::class);

    Route::get('permission-groups/{id}', [PermissionGroupController::class, 'show']);
    Route::post('permission-groups', [PermissionGroupController::class, 'store']);

    Route::get('role-permissions/{id}', [RolePermissionController::class, 'show']);
    Route::post('role-permissions', [RolePermissionController::class, 'store']);

    Route::get('/designations/datalist', [DesignationController::class, 'datalist']);
    Route::resource('designations', DesignationController::class);

    Route::get('/departments/datalist', [DepartmentController::class, 'datalist']);
    Route::resource('departments', DepartmentController::class);

    Route::get('/private/formats/{file}', [PrivateController::class, 'formats']);

    Route::get('/display-board/datalist', [DisplayBoardController::class, 'datalist']);
    Route::post('/display-board/reorder', [DisplayBoardController::class, 'reorder']);
    Route::post('/display-board/update-case-status', [DisplayBoardController::class, 'updateCaseStatus']);
    Route::post('/display-board/update-display', [DisplayBoardController::class, 'updateDisplay']);
    Route::post('/display-board/stop-display', [DisplayBoardController::class, 'stopDisplay']);
    Route::get('/display-board/sort-by-item-number',[DisplayBoardController::class, 'sortByItemNumber']);
    Route::get('/display-board/restart-session',[DisplayBoardController::class, 'restartSession']);
    Route::post('/display-board/clear-list',[DisplayBoardController::class, 'clearList']);
    Route::get('/display-board/get-case-numbers', [DisplayBoardController::class, 'caseNumbers']);
    Route::get('/display-board/get-case-title', [DisplayBoardController::class, 'caseTitle']);
    Route::post('/display-board/start-session', [DisplayBoardController::class, 'startSession']);
    Route::post('/display-board/next', [DisplayBoardController::class, 'startNext']);
    Route::post('/display-board/stop-session', [DisplayBoardController::class, 'stopSession']);
    Route::get('/display-board/is-multiple-insession', [DisplayBoardController::class, 'isMultipleInSession']);
    Route::post('/display-board/is-insession', [DisplayBoardController::class, 'isInSession']);
    Route::post('/display-board/is-display-started', [DisplayBoardController::class, 'isDisplayStarted']);
    Route::resource('display-board', DisplayBoardController::class);
    Route::get('/symbolic-link', function () {
        Artisan::call('storage:link');
    });

    Route::get('/audit-trail/datalist', [AuditTrailController::class, 'datalist']);
    Route::resource('audit-trail', AuditTrailController::class);
});

require __DIR__.'/auth.php';