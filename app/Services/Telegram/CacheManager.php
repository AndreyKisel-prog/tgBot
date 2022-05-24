<?php

namespace App\Services\Telegram;

use Illuminate\Support\Facades\Cache;

class CacheManager
{
    /**
     * @param string $userName
     * @param string $text
     * @return bool
     */
    public function setCacheSearchWord(string $userName, string $text): bool
    {
        return Cache::put('last_search_word_' . $userName, $text, 1440);
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @param string $userName
     * @return bool
     */
    public function setCacheLocation(float $latitude, float $longitude, string $userName): bool
    {
        Cache::put('last_latitude_' . $userName, $latitude, 1440);
        return Cache::put('last_longitude_' . $userName, $longitude, 1440);
    }

    /**
     * @param int $radius
     * @param string $userName
     * @return bool
     */
    public function setCacheRadius(int $radius, string $userName): bool
    {
        return Cache::put('last_radius_' . $userName, $radius, 1440);
    }

    /**
     * @param string $userName
     * @return mixed
     */
    public function getLatitude(string $userName)
    {
        return Cache::get('last_latitude_' . $userName);
    }

    /**
     * @param string $userName
     * @return void
     */
    public function getLongitude(string $userName)
    {
        return Cache::get('last_longitude_' . $userName);
    }

    /**
     * @param string $userName
     * @return void
     */
    public function getSearchWord(string $userName)
    {
        return Cache::get('last_search_word_' . $userName);
    }

    /**
     * @param string $userName
     * @return void
     */
    public function getRadius(string $userName)
    {
        return Cache::get('last_radius_' . $userName);
    }

}

