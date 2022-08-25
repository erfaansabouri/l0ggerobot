<?php

namespace App\Spiders;

use Generator;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;
use RoachPHP\Downloader\Middleware\UserAgentMiddleware;
use RoachPHP\Extensions\LoggerExtension;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Http\Request;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use Symfony\Component\DomCrawler\Crawler;

class DivarSpider extends BasicSpider
{
    public array $startUrls = [
        'https://divar.ir/s/shiraz'
    ];

    public array $downloaderMiddleware = [
        RequestDeduplicationMiddleware::class,
        [UserAgentMiddleware::class, ['userAgent' => 'Mozilla/5.0 (compatible; RoachPHP/0.1.0)']],
    ];

    public array $spiderMiddleware = [
        //
    ];

    public array $itemProcessors = [
        //
    ];

    public array $extensions = [
        LoggerExtension::class,
        StatsCollectorExtension::class,
    ];

    public int $concurrency = 2;

    public int $requestDelay = 2;


    public function parse(Response $response): Generator
    {
        $post_tokens = $response->filter('div.waf972 > a')
            ->each(function (Crawler $crawler) {
                return [
                    'text' => $this->extractPostTokenFromUri($crawler->attr('href')),
                ];
            });

        yield $this->item([
            'post_tokens' => $post_tokens,
        ]);
    }

    private function extractPostTokenFromUri($uri)
    {
        $parts = explode('/', $uri);
        return end($parts);
    }
}
