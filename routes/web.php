<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AbsenceRequestController;
use App\Http\Controllers\PermissionRequestController;
use App\Http\Controllers\OverTimeRequestsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceRecordController;
use App\Http\Controllers\MacAddressController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SalarySheetController;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExampleMail;

Route::get('/send-mail', function () {
    $data = [
        'name' => 'Recipient Name',
        'message' => 'This is a test email.'
    ];

    Mail::to('ahmeddfathy087@gmail.com')->send(new ExampleMail($data));

    return 'Email Sent Successfully!';
});


Route::get('/salary-sheets', [SalarySheetController::class, 'index'])->name('salary-sheets.index');
Route::post('/salary-sheets/upload', [SalarySheetController::class, 'upload'])->name('salary-sheets.upload');

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('/');

Route::get('/welcome', function () {
    return "hello";
})->name('welcome');

Route::get('/mac-addresses', [MacAddressController::class, 'getMacAddresses']);

// Authentication routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Employee routes
Route::middleware(['auth'])->group(function () {
    Route::resource('/absence-requests', AbsenceRequestController::class);
    Route::post('/absence-requests/{absenceRequest}/status', [AbsenceRequestController::class, 'updateStatus'])
        ->name('absence-requests.updateStatus');
    Route::resource('/permission-requests', PermissionRequestController::class);
});

// Manager routes
Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::resource('leaves', LeaveController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::resource('overtime-requests', OverTimeRequestsController::class);
    Route::patch('/absence-requests/{absenceRequest}/reset-status', [AbsenceRequestController::class, 'resetStatus'])
        ->name('absence-requests.reset-status');
    Route::patch('/absence-requests/{id}/modify', [AbsenceRequestController::class, 'modifyResponse'])
        ->name('absence-requests.modify');
    Route::post('/permission-requests/{permissionRequest}/update-status', [PermissionRequestController::class, 'updateStatus'])
        ->name('permission-requests.update-status');
    Route::patch('/permission-requests/{permissionRequest}/reset-status', [PermissionRequestController::class, 'resetStatus'])
        ->name('permission-requests.reset-status');
    Route::patch('/permission-requests/{permissionRequest}/modify', [PermissionRequestController::class, 'modifyResponse'])
        ->name('permission-requests.modify');
    Route::patch('/permission-requests/{permissionRequest}/return-status', [PermissionRequestController::class, 'updateReturnStatus'])
    ->name('permission-requests.update-return-status');


    Route::patch('/overtime-requests/{overTimeRequest}/respond', [OverTimeRequestsController::class, 'updateStatus'])
        ->name('overtime-requests.respond');
    Route::patch('/overtime-requests/{overTimeRequest}/reset-status', [OverTimeRequestsController::class, 'resetStatus'])
        ->name('overtime-requests.reset-status');
    Route::patch('/overtime-requests/{overTimeRequest}/modify', [OverTimeRequestsController::class, 'modifyResponse'])
        ->name('overtime-requests.modify');
    Route::patch('/overtime-requests/{id}', [OverTimeRequestsController::class, 'update']);
    Route::delete('/overtime-requests/{overtimeRequest}', [OverTimeRequestsController::class, 'destroy'])->name('overtime-requests.destroy');
});

// Shared routes (Manager & Employee)
Route::middleware(['auth', 'role:manager,employee'])->group(function () {
    // Attendance
    Route::get('attendances/create', [AttendanceController::class, 'create'])->name('attendances.create');
    Route::post('attendances', [AttendanceController::class, 'store'])->name('attendances.store');
    // Leave
    Route::get('leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('leaves', [LeaveController::class, 'store'])->name('leaves.store');
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount']);
    Route::get('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.mark-as-read');



});

// User management routes
Route::get('/users', [UserController::class, 'index'])->name('user.index');
Route::post('/users/import', [UserController::class, 'import'])->name('user.import');
Route::post('/users/import2', [UserController::class, 'import2'])->name('user.import2');

// Attendance record routes
Route::get('/attendance', [AttendanceRecordController::class, 'index'])->name('attendance.index');
Route::post('/attendance/import', [AttendanceRecordController::class, 'import'])->name('attendance.import');
Route::get('/user/{employee_id}/attendance-report', [DashboardController::class, 'generateAttendancePDF'])
    ->name('user.downloadAttendanceReport');



Route::get('/salary-sheet/{userId}/{month}/{filename}', function ($userId, $month, $filename) {
    $user = Auth::user();
    if ($user->id != $userId && $user->role != 'manager') {
        abort(403, 'Unauthorized access');
    }
    $filePath = storage_path("app/private/salary_sheets/{$userId}/{$month}/{$filename}");
    if (!file_exists($filePath)) {
        abort(404, 'File not found');
    }
    return response()->file($filePath);
})->middleware('auth');

use App\Http\Controllers\ChatController;
use App\Http\Controllers\OnlineStatusController;
Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::get('/chat/messages/{receiver}', [ChatController::class, 'getMessages']);
Route::post('/chat/send', [ChatController::class, 'sendMessage']);
Route::post('/chat/mark-seen', [ChatController::class, 'markAsSeen']);
Route::post('/status/update', [OnlineStatusController::class, 'updateStatus']);
Route::get('/status/user/{userId}', [OnlineStatusController::class, 'getUserStatus']);

