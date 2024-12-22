<?php

namespace App\Services;

use App\Models\SalarySheet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class SalarySheetService
{
    protected $notificationService;

    // تغيير NotificationService إلى SalaryNotificationService
    public function __construct(SalaryNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function getAllSalarySheets()
    {
        return SalarySheet::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function handleFileUpload($files): array
    {
        $results = [];

        foreach ($files as $file) {
            $results[] = $this->processSingleFile($file);
        }

        return $results;
    }

    private function processSingleFile(UploadedFile $file): array
    {
        $employee_id = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $month = Carbon::now()->format('Y-m');
        $extension = $file->getClientOriginalExtension();

        $path = "salary_sheets/{$employee_id}/{$month}";
        $fileName = "{$employee_id}_{$month}.{$extension}";

        $filePath = Storage::putFileAs($path, $file, $fileName);

        $salarySheet = SalarySheet::create([
            'employee_id' => $employee_id,
            'month' => $month,
            'file_path' => $filePath,
            'original_filename' => $file->getClientOriginalName()
        ]);

        // Get user and create notification
        $employee = User::where('employee_id', $employee_id)->firstOrFail();


        // استخدام SalaryNotificationService بدلاً من NotificationService
        $this->notificationService->createSalarySheetNotification($employee, [
            'id' => $salarySheet->id,
            'month' => $month,
            'filename' => $fileName
        ]);

        return [
            'id' => $salarySheet->id,
            'employee_id' => $employee_id,
            'month' => $month,
            'filename' => $fileName
        ];
    }
}
