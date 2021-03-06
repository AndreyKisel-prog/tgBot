<?php

namespace App\Transformers;

class TomtomTransformer extends BaseTransformer
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }


    /**
     * @return array
     */
    public function handle(): array
    {
        $data         = $this->data;
        $countResults = $data["summary"]["query"] ?? null;
        $rawResults   = $data["results"] ?? null;
        $results      = [];
        if ($countResults) {
            foreach ($rawResults as $item) {
                $place           = $item["poi"] ?? null;
                $name            = $place["name"] ?? null;
                $phone           = $place["phone"] ?? null;
                $url             = $place["url"] ?? null;
                $score           = isset($item["score"]) ? round($item["score"], 2) : null;
                $address         = $item["address"]["freeformAddress"] ?? null;
                $dist            = isset($item["dist"]) ? round($item["dist"]) : null;
                $latitude        = $item["position"]["lat"] ?? null;
                $longitude       = $item["position"]["lon"] ?? null;
                $firmDescription = array(
                    'name'      => $name,
                    'phone'     => $phone,
                    'url'       => $url,
                    'score'     => $score,
                    'address'   => $address,
                    'dist'      => $dist,
                    'latitude'  => $latitude,
                    'longitude' => $longitude,
                );
                $results[]       = $firmDescription;
            }
        }
        $data = [
            'countResults' => $countResults,
            'results'      => $results,
        ];

        return $this->createMessages($data);
    }

    /**
     * @param array $data
     * @return array
     */
    public function createMessages(array $data): array
    {
        $messages = [];
        foreach ($data['results'] as $index => $item) {
            $firmDescription   = [];
            $firmDescription[] = "?????????? ??? " . ++$index . PHP_EOL;
            if (isset($item['name'])) {
                $firmDescription[] = "????????????????: " . $item['name'] . PHP_EOL;
            }
            if (isset($item['score'])) {
                $firmDescription[] = "???????????? ??????????????????: " . $item['score'] . PHP_EOL;
            }
            if (isset($item['dist'])) {
                $firmDescription[] = "???????????????????? ????: " . $item['dist'] . PHP_EOL;
            }
            if (isset($item['phone'])) {
                $firmDescription[] = "??????: " . $item['phone'] . PHP_EOL;
            }
            if (isset($item['address'])) {
                $firmDescription[] = "??????????: " . $item['address'] . PHP_EOL;
            }
            if (isset($item['url'])) {
                $firmDescription[] = "????????: " . $item['url'] . PHP_EOL;
            }
            $firmDescription[] = " " . PHP_EOL;
            $message           = implode(" ", $firmDescription);
            $messages[]        = [
                'message'   => $message,
                'latitude'  => $item['latitude'],
                'longitude' => $item['longitude']
            ];
        }
        return $messages;
    }
}
