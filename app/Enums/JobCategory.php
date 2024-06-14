<?php

namespace App\Enums;

enum JobCategory :string
{
    case Laravel = 'laravel';
    case Vue = 'vuejs';
    case React = 'react';
    case Angular = 'angular';
    case Django = 'django';
    case Flask = 'flask';
    case Express = 'express';
    case Spring = 'spring';
    case RubyOnRails = 'ruby-on-rails';
    case ASPNET = 'aspnet';
    case PHP = 'php';
    case NodeJS = 'nodejs';
    case Python = 'python';


}
