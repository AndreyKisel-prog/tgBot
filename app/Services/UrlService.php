<?php

namespace App\Services;

use App\Models\Url;
use Illuminate\Support\Str;

class UrlService
{
    /**
     * @param string $url
     * @return string
     */
    public static function getShortUrl(string $url): string
    {
        $uuid = (string) Str::uuid();

        $urlModel = Url::firstOrCreate(
            ['url' => $url],
            ['id' => $uuid, 'url' => $url]
        );

        return self::generateShortUrl($urlModel->id);
    }

    /**
     * @param string $uuid
     * @return string
     */
    private static function generateShortUrl(string $uuid): string
    {
        return url('/') . '/' . $uuid;
    }
}
