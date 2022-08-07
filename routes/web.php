<?php

use Illuminate\Support\Facades\Route;
use Telegram\Bot\Traits\Telegram;


Route::post('/endpoint/webhook', function () {
    $update = Telegram::commandsHandler(true);
});

Route::get('/setwebhook', function () {
    $response = Telegram::setWebhook(['url' => 'https://l0ggerobot.viewshow.ir/endpoint/webhook']);
    dd($response);
});
