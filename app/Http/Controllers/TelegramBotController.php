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
        $update = $telegram->getUpdates();
        $chat_id = $update->getMessage()->getChat()->getId();
        $text = $update->getMessage()->getText();
        $reply = 'Hello, '.$text;
        $telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => $reply,
        ]);
    }
}
