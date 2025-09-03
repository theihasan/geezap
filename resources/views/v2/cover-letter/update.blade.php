@extends('v2.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">AI Cover Letter Generator</h1>
            <p class="text-gray-600 mt-2">Create professional, personalized cover letters with AI assistance</p>
        </div>
        
        <livewire:cover-letter-chat />
    </div>
</div>
@endsection