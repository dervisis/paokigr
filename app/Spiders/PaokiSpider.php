<?php
namespace App\Spiders;

use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;


class PaokiSpider extends BasicSpider
{
    /**
     * @var string[]
     */
    public array $startUrls = [
        'https://www.sdna.gr/teams/paok'
    ];

    public function parse(Response $response): \Generator
    {
        $titles = $response->filter('.default-teaser__main-info h3')->each(function ($item) {
            print_r($item->filter('h3')->text());
        });
        /*
        $subtitle = $response
            ->filter('main > div:nth-child(2) p:first-of-type')
            ->text();
        */
        foreach ($titles as $title) {
            yield $this->item(['title' => $title]);
        }
    }
}
