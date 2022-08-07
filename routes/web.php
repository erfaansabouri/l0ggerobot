<?php

use Illuminate\Support\Facades\Route;


Route::post('/endpoint/webhook', function () {
    $update = \Telegram::commandsHandler(true);
});

Route::get('/setwebhook', function () {
    $response = \Telegram::setWebhook(['url' => 'https://l0ggerobot.viewshow.ir/endpoint/webhook']);
    dd($response);
});
