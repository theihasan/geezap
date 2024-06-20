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
        Log::info(session('cover_letters'));
        return response()->json($response);

    }
}
