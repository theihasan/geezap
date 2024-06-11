<?php

namespace App\Enums;

enum Timezone : string
{
    case UTCMinus1200 = 'UTC-12:00';
    case UTCMinus1100 = 'UTC-11:00';
    case UTCMinus1000 = 'UTC-10:00';
    case UTCMinus0930 = 'UTC-09:30';
    case UTCMinus0900 = 'UTC-09:00';
    case UTCMinus0800 = 'UTC-08:00';
    case UTCMinus0700 = 'UTC-07:00';
    case UTCMinus0600 = 'UTC-06:00';
    case UTCMinus0500 = 'UTC-05:00';
    case UTCMinus0430 = 'UTC-04:30';
    case UTCMinus0400 = 'UTC-04:00';
    case UTCMinus0330 = 'UTC-03:30';
    case UTCMinus0300 = 'UTC-03:00';
    case UTCMinus0200 = 'UTC-02:00';
    case UTCMinus0100 = 'UTC-01:00';
    case UTCPlus0000 = 'UTC±00:00';
    case UTCPlus0100 = 'UTC+01:00';
    case UTCPlus0200 = 'UTC+02:00';
    case UTCPlus0300 = 'UTC+03:00';
    case UTCPlus0330 = 'UTC+03:30';
    case UTCPlus0400 = 'UTC+04:00';
    case UTCPlus0430 = 'UTC+04:30';
    case UTCPlus0500 = 'UTC+05:00';
    case UTCPlus0530 = 'UTC+05:30';
    case UTCPlus0545 = 'UTC+05:45';
    case UTCPlus0600 = 'UTC+06:00';
    case UTCPlus0630 = 'UTC+06:30';
    case UTCPlus0700 = 'UTC+07:00';
    case UTCPlus0800 = 'UTC+08:00';
    case UTCPlus0845 = 'UTC+08:45';
    case UTCPlus0900 = 'UTC+09:00';
    case UTCPlus0930 = 'UTC+09:30';
    case UTCPlus1000 = 'UTC+10:00';
    case UTCPlus1030 = 'UTC+10:30';
    case UTCPlus1100 = 'UTC+11:00';
    case UTCPlus1200 = 'UTC+12:00';
    case UTCPlus1245 = 'UTC+12:45';
    case UTCPlus1300 = 'UTC+13:00';
    case UTCPlus1400 = 'UTC+14:00';

    public static function toValues(): array
    {
        return [
            self::UTCMinus1200,
            self::UTCMinus1100,
            self::UTCMinus1000,
            self::UTCMinus0930,
            self::UTCMinus0900,
            self::UTCMinus0800,
            self::UTCMinus0700,
            self::UTCMinus0600,
            self::UTCMinus0500,
            self::UTCMinus0430,
            self::UTCMinus0400,
            self::UTCMinus0330,
            self::UTCMinus0300,
            self::UTCMinus0200,
            self::UTCMinus0100,
            self::UTCPlus0000,
            self::UTCPlus0100,
            self::UTCPlus0200,
            self::UTCPlus0300,
            self::UTCPlus0330,
            self::UTCPlus0400,
            self::UTCPlus0430,
            self::UTCPlus0500,
            self::UTCPlus0530,
            self::UTCPlus0545,
            self::UTCPlus0600,
            self::UTCPlus0630,
            self::UTCPlus0700,
            self::UTCPlus0800,
            self::UTCPlus0845,
            self::UTCPlus0900,
            self::UTCPlus0930,
            self::UTCPlus1000,
            self::UTCPlus1030,
            self::UTCPlus1100,
            self::UTCPlus1200,
            self::UTCPlus1245,
            self::UTCPlus1300,
            self::UTCPlus1400,
        ];
    }
}
