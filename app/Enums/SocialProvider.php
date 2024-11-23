<?php

namespace App\Enums;

enum SocialProvider : string
{
    case GITHUB = 'github';
    case FACEBOOK = 'facebook';
    case TWITTER = 'twitter';
    case GOOGLE = 'google';

}
