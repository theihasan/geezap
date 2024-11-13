<?php

namespace App\Enums;

enum JobType :string
{
    case FULL_TIME = 'fulltime';
    case CONTRACTOR = 'contractor';
    case PART_TIME = 'parttime';

}
