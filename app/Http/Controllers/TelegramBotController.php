<?php

namespace App\Http\Controllers;


use App\Crawlers\ShutterstockCrawler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

class TelegramBotController extends Controller
{
    public function handler()
    {
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        // get webhookupdates
        $updates = $telegram->getWebhookUpdates();
        // get message
        $message = $updates->getMessage();
        // get chat id
        $chat_id = $message->getChat()->getId();
        // get text
        $text = $message->getText();
        // send message

        if (stristr($text, 'img') !== false) {
            $crawler = new ShutterstockCrawler(env('SHUTTERSTOCK_API'));
            $crawler->setQuery('boy');
            $crawler->setPage(1);
            $crawler->setPerPage(10);
            $medias = $crawler->images();
            $results_count = collect($medias->data)->count();
            $image_url = $medias->data[rand(0,$results_count-1)]->assets->preview->url;
            $image_description = $medias->data[rand(0,$results_count-1)]->description;
            // send message
            $telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => $image_url.PHP_EOL.$image_description,
            ]);

            $telegram->sendPhoto([
                'chat_id' => $chat_id,
                'photo' => new InputFile("https://telegram.org/img/t_logo.png"),
            ]);
        }
    }
}
