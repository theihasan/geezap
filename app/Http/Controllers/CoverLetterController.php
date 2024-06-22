<?php

namespace App\Http\Controllers;

use App\Services\AIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CoverLetterController extends Controller
{
    public function coverLetter(Request $request, AIService $service): JsonResponse
    {
        $response = $service->generateCoverLetter($request);
        return response()->json($response);

    }
}
