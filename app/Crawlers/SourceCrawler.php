<?php
namespace App\Crawlers;

abstract class SourceCrawler
{
    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    abstract public function subscriptionInfo();
}
