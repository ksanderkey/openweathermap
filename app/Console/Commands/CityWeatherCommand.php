<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\OpenWeatherMap\CitiesWeatherManger;

class CityWeatherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'city:weather {city}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays weather information for a specified city';

    /**
     * The city weather manager instance.
     */
    protected $citiesWeather;

    /**
     * Create a new command instance.
     *
     * @param CitiesWeatherManger $citiesWeather
     *
     * @return void
     */
    public function __construct(CitiesWeatherManger $citiesWeather)
    {
        parent::__construct();

        $this->citiesWeather = $citiesWeather;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cityName = $this->argument('city');
        $weatherData = $this->citiesWeather->getWeatherByCityName($cityName);
        if ($weatherData) {
            $message = sprintf("City weather data '%s'", json_encode($weatherData->data, JSON_PRETTY_PRINT));
        } else {
            $message = "Nothing found for current city";
        }

        $this->info(sprintf("Information for city '%s':", $cityName));
        $this->comment($message);
    }
}
