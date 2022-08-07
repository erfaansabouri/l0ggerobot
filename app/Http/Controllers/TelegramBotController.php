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

            $new_text = str_ireplace('img', '', $text);
            $crawler = new ShutterstockCrawler(env('SHUTTERSTOCK_API'));
            $crawler->setQuery($new_text);
            $crawler->setPage(1);
            $crawler->setPerPage(30);
            $medias = $crawler->images();
            $results_count = collect($medias->data)->count();
            $image_url = @$medias->data[rand(0,$results_count-1)]->assets->huge_thumb->url;
            $image_description = @$medias->data[rand(0,$results_count-1)]->description;
            // send message
            if (!empty(@$image_url))
            {
                $telegram->sendChatAction([
                    'chat_id' => $chat_id,
                    'action' => 'upload_photo'
                ]);
                $telegram->sendPhoto([
                    'chat_id' => $chat_id,
                    'photo' => new InputFile($image_url),
                    'caption' => $image_description,
                ]);
            }
        }
    }
}
