<?php

namespace App\Console\Commands;

use App\Spiders\DivarSpider;
use App\Spiders\FussballdatenSpider;
use Illuminate\Console\Command;
use RoachPHP\Roach;

class RoachDivarCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roach:divar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Roach::startSpider(DivarSpider::class);

        return 0;
    }
}
