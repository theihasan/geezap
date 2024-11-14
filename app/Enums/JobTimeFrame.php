<?php

namespace App\Enums;

enum JobTimeFrame :string
{
    case TODAY = 'today';
    case THREEDAYS = '3days';
    case WEEK = 'week';
    case MONTH = 'month';
}
