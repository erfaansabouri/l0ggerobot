<?php

use Illuminate\Support\Facades\Route;


Route::post('/endpoint/webhook', [\App\Http\Controllers\TelegramBotController::class, 'handler']);

Route::get('/setwebhook', function () {
    $response = \Telegram::setWebhook(['url' => 'https://l0ggerobot.viewshow.ir/endpoint/webhook']);
    dd($response);
});
