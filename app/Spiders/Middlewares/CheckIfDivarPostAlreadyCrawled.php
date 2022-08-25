<?php

namespace App\Spiders\Middlewares;

use App\Models\DivarPost;
use RoachPHP\Http\Response;
use RoachPHP\Spider\Middleware\ResponseMiddlewareInterface;
use RoachPHP\Support\Configurable;

class CheckIfDivarPostAlreadyCrawled implements ResponseMiddlewareInterface
{
    use Configurable;

    public function handleResponse(Response $response): Response
    {
        $token = $response->getMeta('token');
        $post = DivarPost::query()->where('token', $token)->first();
        if ($post) {
            return $response->drop(
                sprintf('Post %s already crawled.', $token)
            );
        }
        return $response;
    }
}
