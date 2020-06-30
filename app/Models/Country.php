<?php

namespace App\Models;

use App\Notifications\NewCases;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

class Country extends Model
{
    public $fillable = ['confirmed', 'deaths', 'recovered', 'name', 'latest_confirmed', 'latest_recovered', 'latest_deaths', 'latest_confirmed_update', 'latest_deaths_update', 'latest_recovered_update'];
    protected function serializeDate(\DateTimeInterface $date) {
        return $date->toDateTimeString();
    }
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::updating(function ($country) {
            $newConfirmed = $country->confirmed !== $country->getOriginal('confirmed');
            $newDeaths = $country->deaths !== $country->getOriginal('deaths');
            $newRecovered = $country->recovered !== $country->getOriginal('recovered');
            if($newConfirmed){
                $country->latest_confirmed = $country->getOriginal('confirmed');
                $country->latest_confirmed_update = Carbon::now();
            }
            if($newDeaths){
                $country->latest_deaths = $country->getOriginal('deaths');
                $country->latest_deaths_update = Carbon::now();
            }
            if($newRecovered){
                $country->latest_recovered = $country->getOriginal('recovered');
                $country->latest_recovered_update = Carbon::now();
            }
            if($newConfirmed || $newDeaths || $newRecovered){
                Notification::send($country->subscribes, new NewCases([
                    'country_id' => $country->id,
                    'country_name' => $country->name,
                    'new_confirmed' => $country->confirmed,
                    'new_deaths' => $country->deaths,
                    'new_recoveries' => $country->recovered
                ]));
            }
        });
    }

    public function subscribes(){
        return $this->hasMany(Subscribe::class);
    }
}
