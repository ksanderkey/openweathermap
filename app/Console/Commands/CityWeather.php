<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CityWeather extends Command
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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cityName = $this->argument('city');

        $this->info(sprintf("You've entered '%s'", $cityName));
    }
}
