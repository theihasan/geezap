<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $meta->title }}</title>
    <meta name="title" content="{{ $meta->title }}">
    <meta name="description" content="{{ $meta->description }}">
    <meta name="keywords" content="{{ $meta->keywords }}">

    <!-- Theme initialization script to prevent flash of unstyled content -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const html = document.documentElement;
            
            // Remove any existing theme classes
            html.classList.remove('dark', 'light');
            
            if (savedTheme === 'dark') {
                html.classList.add('dark');
            } else {
                html.classList.add('light');
            }
        })();
    </script>

    <!-- Favicon - Preloaded -->
    <link rel="preload" href="{{asset('assets/images/favicon.ico')}}" as="image" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/images/favicon.ico')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/images/favicon.ico')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/images/favicon.ico')}}">
    
    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    
    <!-- Critical CSS - Inline for faster rendering -->
    <style>
        body { @apply antialiased text-gray-200; }
        .btn-primary { @apply px-6 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg hover:opacity-90 transition-opacity; }
    </style>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css"
        integrity="sha512-vebUliqxrVkBy3gucMhClmyQP9On/HAWQdKDXRaAlb/FKuTbxkjPKUyqVOxAcGwFDka79eTF+YXwfke1h3/wfg=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
        media="print" onload="this.media='all'" />

    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&family=Oxanium:wght@200;600;700&display=swap" 
          rel="stylesheet"
          media="print" onload="this.media='all'">

    <script charset="UTF-8" src="//web.webpushs.com/js/push/03b87feb48aa4902b24d437f1551c5c8_1.js" async defer></script>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ $meta->og->type }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $meta->og->title }}">
    <meta property="og:description" content="{{ $meta->og->description }}">
    <meta property="og:image" content="{{ $meta->og->image ?? asset('assets/images/favicon.ico') }}">
    <meta property="og:site_name" content="Geezap">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $meta->twitter->title }}">
    <meta property="twitter:description" content="{{ $meta->twitter->description }}">
    <meta property="twitter:image" content="{{ $meta->twitter->image ?? asset('assets/images/favicon.ico') }}">

    <!-- Discord -->
    <meta property="discord:title" content="{{ $meta->discord->title }}">
    <meta property="discord:description" content="{{ $meta->discord->description }}">
    <meta property="discord:image" content="{{ $meta->discord->image ?? asset('assets/images/favicon.ico') }}">
    <meta name="theme-color" content="#5865F2">

    @if($meta->structuredData)
        <script type="application/ld+json">
            {!! json_encode($meta->structuredData->toArray()) !!}
        </script>
    @endif

    <!-- Vite assets with preload hints -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles

    @stack('extra-css')
</head>
