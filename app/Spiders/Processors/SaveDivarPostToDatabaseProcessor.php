<?php

namespace App\Spiders\Processors;


use App\Models\DivarPost;
use RoachPHP\ItemPipeline\ItemInterface;
use RoachPHP\ItemPipeline\Processors\CustomItemProcessor;
use RoachPHP\ItemPipeline\Processors\ItemProcessorInterface;
use RoachPHP\Support\Configurable;

class SaveDivarPostToDatabaseProcessor implements ItemProcessorInterface
{
    use Configurable;

    public function processItem(ItemInterface $item): ItemInterface
    {
        $token = $item->get('token');
        $title = $item->get('title');
        $subtitle = str_replace('لحظاتی پیش', '', $item->get('subtitle'));
        $description = $item->get('description', null);
        $meta_data = $item->get('meta_data', null);
        $image = $item->get('image', null);


        if (empty($token) || strlen($token) < 3) {
            return $item->drop(
                sprintf('Wrong token.')
            );
        }

        DivarPost::query()
            ->firstOrCreate(['token' => $token],[
                'title' => $title,
                'subtitle' => $subtitle,
                'description' => $description,
                'meta_data' => json_encode($meta_data,JSON_UNESCAPED_UNICODE),
                'image' => $image,
            ]);

        return $item;
    }

    private function defaultOptions(): array
    {
        // If not overwritten by the user, the default threshold
        // is 4. Any game with fewer goals than that will get
        // dropped.
        return [
            'threshold' => 0
        ];
    }
}
