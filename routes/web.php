<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\MacAddressController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\AbsenceRequestController;
use App\Http\Controllers\PermissionRequestController;
use App\Http\Controllers\ManagerPermissionController;

Route::get('/welcome', function(){return "hello";}) -> name('welcome');
Route::middleware(['auth'])->group(function () {
    Route::resource('/absence-requests', AbsenceRequestController::class);
    Route::post('/absence-requests/{absenceRequest}/status', [AbsenceRequestController::class, 'updateStatus'])->name('absence-requests.updateStatus');
    Route::resource('/permission-requests', PermissionRequestController::class);
    Route::post('/permission-requests/respond/{request}', [PermissionRequestController::class, 'updateStatus'])->name('permission-requests.updateStatus');


});

// Public routes
Route::get('/mac-addresses', [MacAddressController::class, 'getMacAddresses']);

// Home route
Route::get('/', function () {
    return view('welcome');
})->name('/');

// Authenticated routes with Jetstream
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Move the dashboard route outside the conditional logic
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Manager-specific routes (attendance and leave management)
Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::resource('leaves', LeaveController::class)->names([
        'index' => 'leaves.index',
        'create' => 'leaves.create',
        'store' => 'leaves.store',
        'show' => 'leaves.show',
        'edit' => 'leaves.edit',
        'update' => 'leaves.update',
        'destroy' => 'leaves.destroy',
    ]);


    Route::resource('attendances', AttendanceController::class)->names([
        'index' => 'attendances.index',
        'create' => 'attendances.create',
        'store' => 'attendances.store',
        'show' => 'attendances.show',
        'edit' => 'attendances.edit',
        'update' => 'attendances.update',
        'destroy' => 'attendances.destroy',
    ]);

    Route::get('/manager/permissions', [ManagerPermissionController::class, 'index'])
    ->name('manager.permissions.index');
Route::put('/manager/permissions/{permission}/response', [ManagerPermissionController::class, 'updateResponse'])
    ->name('manager.permissions.update-response');
Route::delete('/manager/permissions/{permission}/response', [ManagerPermissionController::class, 'deleteResponse'])
    ->name('manager.permissions.delete-response');
    Route::patch('/absence-requests/{absenceRequest}/reset-status', [AbsenceRequestController::class, 'resetStatus'])->name('absence-requests.reset-status');
    Route::patch('/absence-requests/{id}/modify', [AbsenceRequestController::class, 'modifyResponse'])->name('absence-requests.modify');

});

// Shared routes for both managers and employees
Route::middleware(['auth', 'role:manager,employee'])->group(function () {
    // Attendance routes
    Route::get('attendances/create', [AttendanceController::class, 'create'])->name('attendances.create');
    Route::post('attendances', [AttendanceController::class, 'store'])->name('attendances.store');

    // Leave routes
    Route::get('leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('leaves', [LeaveController::class, 'store'])->name('leaves.store');
});
