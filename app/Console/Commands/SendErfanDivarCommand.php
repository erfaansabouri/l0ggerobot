<?php

namespace App\Console\Commands;

use App\Models\DivarPost;
use Illuminate\Console\Command;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

class SendErfanDivarCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'erfan:divar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        $divar_post = DivarPost::query()
            ->whereNull('sent_to_telegram_at')
            ->where(function ($q){
                $q->where('title', 'like', '%'.'گیم'.'%')
                    ->orWhere('title', 'like', '%'.'گیمینگ'.'%')
                    ->orWhere('title', 'like', '%'.'tuf'.'%')
                    ->orWhere('title', 'like', '%'.'لپ تاپ'.'%')
                    ->orWhere('description', 'like', '%'.'گیم'.'%')
                    ->orWhere('description', 'like', '%'.'گیمینگ'.'%')
                    ->orWhere('description', 'like', '%'.'tuf'.'%')
                    ->orWhere('description', 'like', '%'.'لپ تاپ'.'%');
            })->first();
        if (!empty($divar_post->image))
        {
            $telegram->sendPhoto([
                'chat_id' => "315799454",
                'photo' => new InputFile($divar_post->image),
                'caption' => $divar_post->telegramText(),
            ]);
        }
        else
        {
            $telegram->sendMessage([
                'chat_id' => "315799454",
                'text' => $divar_post->telegramText(),
            ]);
        }

        $divar_post->update([
            'sent_to_telegram_at' => now()
        ]);
        return 0;
    }
}
