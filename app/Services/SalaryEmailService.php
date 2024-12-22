<?php
namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SalaryEmailService
{
    public function sendSalarySheet($file)
    {
        $emailAddress = 'ahmeddfathy087@gmail.com';
        $totalEmails = 70;
        $batchSize = 10; // عدد الرسائل في كل دفعة
        $delay = 2; // التأخير بين الدفعات (بالثواني)

        try {
            for ($i = 0; $i < $totalEmails; $i++) {
                Mail::raw('Please find the salary sheet attached.', function ($message) use ($file, $emailAddress) {
                    $message->to($emailAddress)
                        ->subject('Salary Sheet')
                        ->attach($file->getRealPath(), [
                            'as' => $file->getClientOriginalName(),
                            'mime' => $file->getMimeType(),
                        ]);
                });

                // التأخير بعد كل رسالة
                if (($i + 1) % $batchSize === 0) {
                    sleep($delay); // انتظار بين الدفعات
                }
            }

            return [
                'success' => true,
                'message' => "$totalEmails emails sent successfully"
            ];
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to send emails: ' . $e->getMessage()
            ];
        }
    }
}
