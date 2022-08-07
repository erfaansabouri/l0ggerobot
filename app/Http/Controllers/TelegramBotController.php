<?php

namespace App\Http\Controllers;


use App\Crawlers\ShutterstockCrawler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;

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

        // check if text contains word "img"
        if (strpos($text, 'img') !== false) {
            $crawler = new ShutterstockCrawler(env('SHUTTERSTOCK_API'));
            $crawler->setQuery('boy');
            $medias = $crawler->images();
            $first_image_url = $medias->data[0]->assets->preview->url;
            $telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => $first_image_url
            ]);
        } else {
            // send message
            $telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Send me a text with the word "img" to get images.'
            ]);
        }

        $telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => $text
        ]);

    }
}
