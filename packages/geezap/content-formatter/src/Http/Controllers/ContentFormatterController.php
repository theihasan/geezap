<?php

namespace Geezap\ContentFormatter\Http\Controllers;

use App\Http\Controllers\Controller;
use Geezap\ContentFormatter\Jobs\FormatContentJob;
use Geezap\ContentFormatter\Models\Package;
use Illuminate\Http\Request;

class ContentFormatterController extends Controller
{
    public function index()
    {
        $packages = Package::latest()
            ->take(20)
            ->get();

        return view('content-formatter::index', compact('packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|min:10',
        ]);

        $package = Package::query()->create([
            'content' => $request->content,
            'status' => 'pending',
        ]);

        FormatContentJob::dispatch($package->id);

        return back()->with('success', 'Content submitted for formatting. It will be processed shortly.');
    }
}