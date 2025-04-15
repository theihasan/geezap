<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactPageController extends Controller
{
    public function __invoke()
    {
        return view("v2.pages.contact", [
            "meta" => (object)[
                "title" => "Contact Us | Geezap - Tech Job Board",
                "description" => "Get in touch with the Geezap team. We're here to help with any questions about our tech job board platform.",
            "keywords" => "contact geezap, tech jobs contact, job board help",
            "og" => (object)[
                "type" => "website",
                "title" => "Contact Geezap - Tech Job Board",
                "description" => "Get in touch with the Geezap team. We're here to help with any questions.",
                "image" => null
            ],
            "twitter" => (object)[
        "title" => "Contact Geezap - Tech Job Board",
        "description" => "Get in touch with the Geezap team. We're here to help with any questions.",
                "image" => null
            ],
            "discord" => (object)[
                "title" => "Contact Geezap - Tech Job Board",
                "description" => "Get in touch with the Geezap team. We're here to help with any questions.",
                "image" => null
            ],
            "structuredData" => null
        ]
    ]);
    }
}
