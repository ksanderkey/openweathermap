<?php

namespace App\OpenWeatherMap;
use App\CitiesWeather;

class CitiesWeatherManger
{
    /**
     * @var mixed|string
     */
    protected $apiUrl = '';

    /**
     * @var mixed|string
     */
    protected $apiKey = '';

    /**
     * CitiesWeatherManger constructor.
     * @param string $apiUrl
     * @param string $apiKey
     */
    public function __construct($apiUrl = '', $apiKey = '')
    {
        if (empty($apiUrl)) {
            $this->apiUrl = config('app.openweathermap_api_url');
        }

        if (empty($apiKey)) {
            $this->apiKey = config('app.openweathermap_api_key');
        }
    }

    /**
     * @return mixed|string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @param mixed|string $apiUrl
     */
    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    /**
     * @return mixed|string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param mixed|string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getWeatherByCityName($name)
    {
        $cityData = CitiesWeather::getByName($name);
        $isStored = $cityData && $cityData->updated_at->isToday();
        if (!$isStored) {
            $result = $this->fetchData($this->buildUrl($name));
            if ($result) {
                $weatherData = json_decode($result, true);

                if (!$cityData) {
                    $cityData = new CitiesWeather();
                    $cityData->name = $name;
                }

                $cityData->data = $weatherData;
                $cityData->save();
            }
        }

        return $cityData;
    }

    /**
     * @param $query
     * @return string
     */
    protected function buildUrl($query)
    {
        $urlQuery = 'q=' . urlencode($query);
        $url = $this->apiUrl . "$urlQuery&units=metric&APPID=" . $this->apiKey;

        return $url;
    }

    /**
     * @param $url
     * @return mixed
     *
     * @todo separate fetcher logic from manager
     */
    protected function fetchData($url)
    {
        // if curl not accessible
        if (!function_exists('curl_version')) {
            $content = @file_get_contents($url);
            if (false === strpos($http_response_header[0], "200")) {
                $content = false;
            }

            return $content;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $content = curl_exec($ch);
        if (200 !== curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
            $content = false;
        }

        curl_close($ch);

        return $content;
    }

}