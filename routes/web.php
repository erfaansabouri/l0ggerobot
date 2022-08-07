<?php

use Illuminate\Support\Facades\Route;
use Telegram\Bot\Traits\Telegram;


Route::post('/endpoint/webhook', function () {
    $update = Telegram::commandsHandler(true);
});
