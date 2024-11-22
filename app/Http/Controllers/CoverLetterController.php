<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateCoverLetterJob;
use Illuminate\Http\Request;

class CoverLetterController extends Controller
{
    public function coverLetter(Request $request): void
    {
        GenerateCoverLetterJob::dispatch(auth()->user(), $request->all());
    }
}
