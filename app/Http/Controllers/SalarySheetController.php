<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalarySheetUploadRequest;
use App\Services\SalaryEmailService;
use App\Services\SalarySheetService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SalarySheetController extends Controller
{
    protected $salaryEmailService;
    protected $salarySheetService;

    public function __construct(SalaryEmailService $salaryEmailService ,SalarySheetService $salarySheetService)
    {
        $this->salaryEmailService = $salaryEmailService;
        $this->salarySheetService = $salarySheetService;
    }

    public function index(): View
    {
        $salarySheets = $this->salarySheetService->getAllSalarySheets();
        return view('salary-sheets.index', compact('salarySheets'));
    }

    public function upload(SalarySheetUploadRequest $request): JsonResponse
    {
        try {
            $file = $request->file('files')[0]; // Get the first file
            $result = $this->salaryEmailService->sendSalarySheet($file);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
}




