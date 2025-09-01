@extends('v2.layouts.app')
@section('content')
    <!-- Header Section -->
    <header class="bg-gray-50 dark:bg-[#12122b] border-b border-gray-200 dark:border-gray-800 py-6">
        <div class="max-w-7xl mx-auto px-6">
            <h1 class="text-3xl font-oxanium-semibold text-gray-900 dark:text-white">Browse Job Categories</h1>
            <p class="text-gray-600 dark:text-gray-300 font-ubuntu-regular">Explore a wide range of specialized categories to find the role that best suits your skills.</p>
        </div>
    </header>

    <!-- Categories Grid Section -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-6">
            @if($jobCategories->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-600 dark:text-gray-300 font-ubuntu-regular">No categories found</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($jobCategories as $category)
                        <div class="group bg-white dark:bg-[#1a1a3a] p-6 rounded-2xl border
                        border-gray-200 dark:border-gray-700 hover:border-blue-500/50 dark:hover:border-pink-500/50 transition">
                            <a href="{{ route('job.index', ['category' => $category->id]) }}"
                               class="flex flex-col items-start text-left font-ubuntu-regular">
                                <div class="w-14 h-14 bg-blue-500/10 dark:bg-pink-500/10 rounded-xl flex
                                items-center justify-center mb-4 group-hover:bg-blue-500/20 dark:group-hover:bg-pink-500/20">
                                    @if($category->category_image)
                                        <img src="{{ url($category->category_image)}}"
                                             alt="{{ $category->name }}" class="w-8 h-8 object-contain" loading="lazy">
                                    @else
                                        <i class="las la-briefcase text-2xl text-blue-600 dark:text-pink-300"></i>
                                    @endif
                                </div>
                                <h3 class="text-xl font-semibold font-oxanium-semibold text-gray-900 dark:text-white mb-2">
                                    {{ ucwords($category->name) }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-300">
                                    {{ $category->jobs_count }} {{ Str::plural('position', $category->jobs_count) }}
                                </p>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
