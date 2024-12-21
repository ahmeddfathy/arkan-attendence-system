<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalarySheetUploadRequest;
use App\Services\SalarySheetService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SalarySheetController extends Controller
{
    protected $salarySheetService;

    public function __construct(SalarySheetService $salarySheetService)
    {
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
            $result = $this->salarySheetService->handleFileUpload($request->file('files'));
            return response()->json([
                'success' => true,
                'message' => 'Files uploaded successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
}