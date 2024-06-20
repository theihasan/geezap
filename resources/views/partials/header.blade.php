<!DOCTYPE html>
<html lang="en" class="light scroll-smooth" dir="ltr">


<head>
    <meta charset="UTF-8">
    <title>@yield('page-title', 'Geezap - Personalized Job Arggrigator')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Job Listing Landing Template" name="description">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="Job, CV, Career, Resume, Job Portal" name="keywords">
    <meta name="author" content="Shreethemes">
    <meta name="website" content="https://geezap.com/">
    <meta name="email" content="support@geezap.com">
    <meta name="version" content="1.4.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">

    <!-- Css -->
    <link href="{{asset('assets/libs/tobii/css/tobii.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/libs/choices.js/public/assets/styles/choices.min.css')}}" rel="stylesheet">
    <!-- Main Css -->
    <link href="{{asset('assets/libs/%40iconscout/unicons/css/line.css')}}" type="text/css" rel="stylesheet">
    <link href="{{asset('assets/libs/%40mdi/font/css/materialdesignicons.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/tailwind.min.css')}}" rel="stylesheet" type="text/css">
    @stack('extra-css')

</head>
