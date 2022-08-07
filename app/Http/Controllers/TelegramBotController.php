<?php

namespace App\Http\Controllers;


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
        $telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => $text
        ]);

    }
}
