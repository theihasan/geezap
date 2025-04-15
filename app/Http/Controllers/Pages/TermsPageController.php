<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TermsPageController extends Controller
{
    public function __invoke()
    {
        return view('v2.pages.terms-condition', [
            'meta' => (object)[
                'title' => 'Terms of Service | Geezap - Tech Job Board',
                'description' => 'Read the terms and conditions for using Geezap, the tech job board platform.',
                'keywords' => 'terms of service, geezap terms, conditions, tech job board',
                'og' => (object)[
                    'type' => 'website',
                    'title' => 'Terms of Service | Geezap - Tech Job Board',
                    'description' => 'Read the terms and conditions for using Geezap.',
                    'image' => null
                ],
                'twitter' => (object)[
                    'title' => 'Terms of Service | Geezap - Tech Job Board',
                    'description' => 'Read the terms and conditions for using Geezap.',
                    'image' => null
                ],
                'discord' => (object)[
                    'title' => 'Terms of Service | Geezap - Tech Job Board',
                    'description' => 'Read the terms and conditions for using Geezap.',
                    'image' => null
                ],
                'structuredData' => null
            ]
        ]);
    }
}
