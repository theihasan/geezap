<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AboutPageController extends Controller
{
    public function __invoke()
    {
        return view('v2.pages.about', [
            'meta' => (object)[
                'title' => 'About Us | Geezap - Tech Job Board',
                'description' => 'Learn about Geezap, a tech job board created to empower technology professionals by making the job search smarter, faster, and more personal.',
                'keywords' => 'tech jobs, about geezap, job board, developer jobs',
                'og' => (object)[
                    'type' => 'website',
                    'title' => 'About Geezap - Tech Job Board',
                    'description' => 'Learn about Geezap, a tech job board created to empower technology professionals.',
                    'image' => null
                ],
                'twitter' => (object)[
                    'title' => 'About Geezap - Tech Job Board',
                    'description' => 'Learn about Geezap, a tech job board created to empower technology professionals.',
                    'image' => null
                ],
                'discord' => (object)[
                    'title' => 'About Geezap - Tech Job Board',
                    'description' => 'Learn about Geezap, a tech job board created to empower technology professionals.',
                    'image' => null
                ],
                'structuredData' => null
            ]
        ]);
    }
}
