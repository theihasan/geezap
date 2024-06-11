<?php

namespace App\Enums;

enum SkillProfeciency : string
{
    CASE PROFICIENT = 'proficient';
    CASE INTERMEDIATE = 'intermediate';
    CASE BEGINNER = 'beginner';

    public static function toValues()
    {
        return [
            self::PROFICIENT,
            self::INTERMEDIATE,
            self::BEGINNER,
        ];
    }
}
