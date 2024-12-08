<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $meta->title }}</title>
    <meta name="title" content="{{ $meta->title }}">
    <meta name="description" content="{{ $meta->description }}">
    <meta name="keywords" content="{{ $meta->keywords }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/images/favicon.ico')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/images/favicon.ico')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/images/favicon.ico')}}">

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

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles

    @stack('extra-css')
</head>
