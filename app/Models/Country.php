<?php

namespace App\Models;

use App\Notifications\NewCases;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

class Country extends Model
{
    public $guarded =  [];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::updating(function ($country) {
            $newConfirmed = $country->new_confirmed !== $country->getOriginal('new_confirmed');
            $newDeaths = $country->new_deaths !== $country->getOriginal('new_deaths');
            if($newConfirmed || $newDeaths){
                Notification::send($country->subscribes, new NewCases([
                    'country_id' => $country->id,
                    'country_name' => $country->name,
                    'new_confirmed' => $country->new_confirmed,
                    'new_deaths' => $country->new_deaths,
                ]));
            }
        });
        static::created(function ($country){
            $collection = Country::select(['id', 'name'])->get();
            $collection = $collection->sortByDesc(function($country){
                return intval(str_replace(',', '', $country->confirmed));
            });
            Cache::forever('countriesIndex', $collection->values()->all());
        });
    }

    public function subscribes(){
        return $this->hasMany(Subscribe::class);
    }
}
