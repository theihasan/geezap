<?php

namespace App\Enums;

enum EmailFrequency: string
{
    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
}