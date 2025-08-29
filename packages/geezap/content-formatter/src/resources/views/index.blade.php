<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Formatter</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Content Formatter</h1>
                <p class="text-gray-600 mb-6">Paste job content from Facebook or other sources to automatically format and add to job listings.</p>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('content-formatter.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            Job Content
                        </label>
                        <textarea
                            id="content"
                            name="content"
                            rows="12"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                            placeholder="Paste your job content here..."
                            required
                        >{{ old('content') }}</textarea>
                        <p class="mt-2 text-sm text-gray-500">
                            Copy and paste job postings from Facebook, LinkedIn, or other sources. The AI will automatically extract and format the relevant information.
                        </p>
                    </div>

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200"
                        >
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Submit for Processing
                        </button>
                    </div>
                </form>
            </div>

            <!-- Recent Submissions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Submissions</h2>
                
                @if($packages->isEmpty())
                    <p class="text-gray-600">No recent submissions.</p>
                @else
                    <div class="space-y-4">
                        @foreach($packages as $package)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <div class="text-sm text-gray-600 mb-1">
                                            {{ $package->created_at->format('M j, Y \a\t g:i A') }}
                                        </div>
                                        <div class="text-gray-800 line-clamp-2">
                                            {{ Str::limit($package->content, 150) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'processing' => 'bg-blue-100 text-blue-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'failed' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$package->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($package->status) }}
                                        </span>
                                    </div>
                                </div>
                                
                                @if($package->status === 'completed' && $package->metadata && isset($package->metadata['job_listing_id']))
                                    <div class="mt-2 text-sm text-green-600">
                                        ✓ Created Job Listing #{{ $package->metadata['job_listing_id'] }}
                                    </div>
                                @endif
                                
                                @if($package->status === 'failed' && $package->metadata && isset($package->metadata['error']))
                                    <div class="mt-2 text-sm text-red-600">
                                        ✗ Error: {{ $package->metadata['error'] }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-2">No submissions yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>