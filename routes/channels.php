<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('cover-letter.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
