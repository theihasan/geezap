<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default SEO Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the default SEO settings for geezap.
    | These values are used when specific meta tags are not provided.
    |
    */

    'defaults' => [
        'title' => env('APP_NAME', 'Geezap'),
        'title_separator' => ' | ',
        'description' => 'Find your dream job with Geezap - AI-powered job aggregation platform unifying listings from LinkedIn, Upwork, Indeed, and ZipRecruiter with smart matching and cover letter generation.',
        'keywords' => 'job search, AI job matching, career platform, job aggregator, remote jobs, tech jobs, LinkedIn jobs, Upwork, Indeed, ZipRecruiter',
        'author' => 'Geezap',
        'robots' => 'index,follow',
        'canonical_url' => null, // Will use current URL if null
    ],

    'images' => [
        'default' => '/assets/images/og-default.jpg',
        'fallback' => '/assets/images/favicon.ico',
        'width' => 1200,
        'height' => 630,
    ],

    'open_graph' => [
        'site_name' => env('APP_NAME', 'Geezap'),
        'locale' => 'en_US',
        'type' => 'website',
    ],

    'twitter' => [
        'site' => '@geezap',
        'creator' => '@geezap',
        'card' => 'summary_large_image',
    ],

    'structured_data' => [
        'organization' => [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => env('APP_NAME', 'Geezap'),
            'url' => env('APP_URL'),
            'logo' => env('APP_URL') . '/assets/images/logo.png',
            'sameAs' => [
                // Add social media profiles here
            ],
        ],
        'website' => [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => env('APP_NAME', 'Geezap'),
            'url' => env('APP_URL'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => env('APP_URL') . '/jobs?search={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Route-specific SEO Configuration
    |--------------------------------------------------------------------------
    |
    | Define SEO settings for specific routes. This allows for fine-tuned
    | control over meta tags for different pages.
    |
    */

    'routes' => [
        'dashboard' => [
            'title' => 'Dashboard | Manage Your Job Search',
            'description' => 'Track your job applications, manage saved jobs, update your profile, and monitor your job search progress. Access personalized job recommendations and AI-generated cover letters.',
            'keywords' => 'job dashboard, application tracking, career management, job search progress',
            'robots' => 'noindex,nofollow', // Dashboard is private
        ],

        'about' => [
            'title' => 'About Geezap | AI-Powered Job Aggregation Platform',
            'description' => 'Learn about Geezap\'s mission to revolutionize job searching with AI-powered matching. We aggregate opportunities from LinkedIn, Upwork, Indeed, and ZipRecruiter to help you find your dream tech job.',
            'keywords' => 'about geezap, AI job matching, job aggregation, career platform, tech recruitment',
        ],

        'contact' => [
            'title' => 'Contact Us | Get Support',
            'description' => 'Need help with your job search? Contact Geezap support for assistance with your account, job applications, or platform features. We\'re here to help you succeed.',
            'keywords' => 'contact geezap, customer support, help, assistance, job search support',
        ],

        'privacy-policy' => [
            'title' => 'Privacy Policy | Your Data Protection',
            'description' => 'Learn how Geezap protects your personal information and job search data. Our comprehensive privacy policy explains data collection, usage, and your rights.',
            'keywords' => 'privacy policy, data protection, user privacy, GDPR compliance, data security',
        ],

        'terms' => [
            'title' => 'Terms of Service | User Agreement',
            'description' => 'Read Geezap\'s terms of service and user agreement. Understand your rights and responsibilities when using our job search platform.',
            'keywords' => 'terms of service, user agreement, legal terms, platform rules, user responsibilities',
        ],

        'cover-letter.update' => [
            'title' => 'AI Cover Letter Generator | Create Professional Cover Letters',
            'description' => 'Generate personalized, professional cover letters with AI technology. Tailor your cover letter to specific job opportunities and increase your application success rate.',
            'keywords' => 'AI cover letter, cover letter generator, job application, professional writing, AI writing assistant',
        ],

        'applications' => [
            'title' => 'My Applications | Track Job Applications',
            'description' => 'View and manage all your job applications in one place. Track application status, follow up dates, and organize your job search efficiently.',
            'keywords' => 'job applications, application tracking, application status, job search management',
            'robots' => 'noindex,nofollow', // Personal data
        ],

        'profile.update' => [
            'title' => 'Update Profile | Manage Your Information',
            'description' => 'Update your professional profile, skills, experience, and preferences. Keep your information current to receive better job recommendations.',
            'keywords' => 'profile update, professional profile, skills management, career preferences',
            'robots' => 'noindex,nofollow', // Personal data
        ],

        'profile.preferences' => [
            'title' => 'Job Preferences | Customize Your Job Search',
            'description' => 'Set your job search preferences including location, salary range, remote work options, and notification settings to receive personalized job recommendations.',
            'keywords' => 'job preferences, job search settings, notifications, personalization, job alerts',
            'robots' => 'noindex,nofollow', // Personal data
        ],
    ],
];