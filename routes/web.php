<?php

use App\Crawlers\ShutterstockCrawler;
use Illuminate\Support\Facades\Route;




Route::post('/endpoint/webhook', [\App\Http\Controllers\TelegramBotController::class, 'handler']);

Route::get('/setwebhook', function () {
    $response = \Telegram::setWebhook(['url' => 'https://l0ggerobot.viewshow.ir/endpoint/webhook']);
    dd($response);
});


Route::get('/test', function () {
    $crawler = new ShutterstockCrawler(env('SHUTTERSTOCK_API'));
    $crawler->setQuery('boy');
    $crawler->setPage(1);
    $crawler->setPerPage(10);
    $medias = $crawler->images();
    dd($medias);
});
