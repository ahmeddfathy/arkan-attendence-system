<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\MacAddressController;
use App\Http\Controllers\DashboardController;

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
