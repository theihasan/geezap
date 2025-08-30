<?php

namespace App\Enums;

enum Role :string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case EDITOR = 'editor';
}
