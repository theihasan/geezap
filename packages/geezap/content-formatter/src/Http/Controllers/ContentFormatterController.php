<?php

namespace Geezap\ContentFormatter\Http\Controllers;

use App\Http\Controllers\Controller;
use Geezap\ContentFormatter\Jobs\FormatContentJob;
use Geezap\ContentFormatter\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContentFormatterController extends Controller
{
    public function index()
    {
        $packages = Package::query()
            ->latest()
            ->take(20)
            ->get();

        return view('content-formatter::index', compact('packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|min:10',
            'apply_link' => 'nullable|string|max:500',
        ]);

        $package = Package::query()->create([
            'content' => $request->content,
            'apply_link' => $request->apply_link,
            'status' => 'pending',
        ]);

        FormatContentJob::dispatch($package->id);
        
        Log::info('ContentFormatterController: Package created and job dispatched', [
            'package_id' => $package->id,
            'content_length' => strlen($package->content),
            'apply_link' => $package->apply_link
        ]);
        return back()->with('success', 'Content submitted for formatting. It will be processed shortly.');
    }
}