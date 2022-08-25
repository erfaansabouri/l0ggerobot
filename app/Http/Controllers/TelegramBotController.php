<?php

namespace App\Http\Controllers;


use App\Crawlers\ShutterstockCrawler;
use App\Models\DivarPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

class TelegramBotController extends Controller
{
    public function handler()
    {
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));

        $this->handleDivarCommand($telegram);
    }

    private function handleDivarCommand($telegram)
    {
        $updates = $telegram->getWebhookUpdates();
        $message = $updates->getMessage();
        $chat_id = $message->getChat()->getId();
        $text = $message->getText();

        // if text contains word divar
        if (stristr($text, 'divar') !== false) {
            $divar_post = DivarPost::query()
                ->whereNull('sent_to_telegram_at')
                ->inRandomOrder()
                ->first();
            $telegram->sendChatAction([
                'chat_id' => $chat_id,
                'action' => 'typing',
            ]);
            if (!empty($divar_post->image))
            {
                $telegram->sendPhoto([
                    'chat_id' => $chat_id,
                    'photo' => new InputFile($divar_post->image),
                    'caption' => $divar_post->telegramText(),
                ]);
            }
            else
            {
                $telegram->sendMessage([
                    'chat_id' => $chat_id,
                    'text' => $divar_post->telegramText(),
                ]);
            }

            $divar_post->update([
                'sent_to_telegram_at' => now()
            ]);
        }
    }

}
