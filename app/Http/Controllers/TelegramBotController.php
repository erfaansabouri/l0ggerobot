<?php

namespace App\Http\Controllers;


use Facade\FlareClient\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramBotController extends Controller
{
    public function handler()
    {
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        $update = $telegram->getUpdates();
        Log::useDailyFiles(storage_path().'/logs/telegram.log');
        Log::info($update);
        $chat_id = $update->getMessage()->getChat()->getId();
        $text = $update->getMessage()->getText();
        $reply = 'Hello, '.$text;
        $telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => $reply,
        ]);
    }
}
