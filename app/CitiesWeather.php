<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CitiesWeather extends Model
{
    /**
     * Get City data by name
     *
     * @param $name
     * @return mixed
     */
    public static function getByName($name) {
        return static::where('name', $name)->first();
    }

    /**
     * @param $value
     * @return string
     */
    public function getDataAttribute($value)
    {
        return unserialize($value);
    }

    /**
     * @param $value
     */
    public function setDataAttribute($value)
    {
        $this->attributes['data'] = serialize($value);
    }
}
