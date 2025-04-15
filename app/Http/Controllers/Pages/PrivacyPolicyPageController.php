<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PrivacyPolicyPageController extends Controller
{
    public function __invoke()
    {
        return view('v2.pages.privacy-policy', [
            'meta' => (object)[
                'title' => 'Privacy Policy | Geezap - Tech Job Board',
                'description' => 'Learn how Geezap protects your privacy and what data we collect when you use our tech job board platform.',
                'keywords' => 'privacy policy, geezap privacy, data protection, tech job board',
                'og' => (object)[
                    'type' => 'website',
                    'title' => 'Privacy Policy | Geezap - Tech Job Board',
                    'description' => 'Learn how Geezap protects your privacy and what data we collect.',
                    'image' => null
                ],
                'twitter' => (object)[
                    'title' => 'Privacy Policy | Geezap - Tech Job Board',
                    'description' => 'Learn how Geezap protects your privacy and what data we collect.',
                    'image' => null
                ],
                'discord' => (object)[
                    'title' => 'Privacy Policy | Geezap - Tech Job Board',
                    'description' => 'Learn how Geezap protects your privacy and what data we collect.',
                    'image' => null
                ],
                'structuredData' => null
            ]
        ]);
    }
}
