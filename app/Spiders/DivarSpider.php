<?php

namespace App\Spiders;

use App\Spiders\Middlewares\CheckIfDivarPostAlreadyCrawled;
use App\Spiders\Processors\SaveDivarPostToDatabaseProcessor;
use Generator;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;
use RoachPHP\Downloader\Middleware\UserAgentMiddleware;
use RoachPHP\Extensions\LoggerExtension;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Http\Request;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Spider\ParseResult;
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
        CheckIfDivarPostAlreadyCrawled::class,
    ];

    public array $itemProcessors = [
        SaveDivarPostToDatabaseProcessor::class,
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
                    'token' => $this->extractPostTokenFromUri($crawler->attr('href')),
                ];
            });

        foreach ($post_tokens as $post_token) {
            $post_url = 'https://divar.ir/v/'.$post_token['token'];
            $request = new Request( 'GET',$post_url, [$this, 'parseDivarPost']);
            yield ParseResult::fromValue($request->withMeta('token', $post_token['token']));
        }
    }

    public function parseDivarPost(Response $response): Generator
    {
        $post_title = $response->filter('div.kt-page-title__title')->text('');
        $post_subtitle = $response->filter('.kt-page-title__subtitle')->text('');
        $post_description = $response->filter('.kt-description-row__text')->text('');
        $meta_keys = $response->filter('.kt-unexpandable-row__title')
            ->each(function (Crawler $crawler) {
                return [
                    'meta_key' => $crawler->text('')
                ];
            });
        $meta_values = $response->filter('.kt-unexpandable-row__value')
            ->each(function (Crawler $crawler) {
                return [
                    'meta_value' => $crawler->text('')
                ];
            });
        $meta_data = collect($meta_keys)->flatten()->zip(collect($meta_values)->flatten())->toArray();
        $post_image = $response->filter('.kt-image-block__image')->count() > 0 ? $response->filter('.kt-image-block__image')->first()->attr('src') : null;
        yield $this->item([
            'token' => $response->getRequest()->getMeta('token'),
            'title' => $post_title,
            'subtitle' => $post_subtitle,
            'description' => $post_description,
            'meta_data' => $meta_data,
            'image' => $post_image,
        ]);
    }

    private function extractPostTokenFromUri($uri)
    {
        $parts = explode('/', $uri);
        return end($parts);
    }
}
