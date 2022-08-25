<?php

use App\Crawlers\ShutterstockCrawler;
use Illuminate\Support\Facades\Route;
use RoachPHP\Roach;


Route::post('/endpoint/webhook', [\App\Http\Controllers\TelegramBotController::class, 'handler']);

Route::get('/setwebhook', function () {
    $response = \Telegram::setWebhook(['url' => 'https://l0ggerobot.viewshow.ir/endpoint/webhook']);
    dd($response);
});


Route::get('/test', function () {
    $items = Roach::collectSpider(\App\Spiders\DivarSpider::class);
    dd($items);
});
