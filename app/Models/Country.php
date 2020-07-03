<?php

namespace App\Models;

use App\Notifications\NewCases;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
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
//            $newRecovered = $country->recovered - $country->getOriginal('recovered');
//            if($newConfirmed){
//                $country->old_confirmed = $country->new_confirmed;
//                $country->new_confirmed = $newConfirmed;
//            }
//            if($newDeaths){
//                $country->old_deaths = $country->new_deaths;
//                $country->new_deaths = $newDeaths;
//            }
//            if($newRecovered){
//                $country->old_recovered = $country->new_recovered;
//                $country->new_recovered = $newRecovered;
//            }
            if($newConfirmed || $newDeaths){
                Notification::send($country->subscribes, new NewCases([
                    'country_id' => $country->id,
                    'country_name' => $country->name,
                    'new_confirmed' => $country->new_confirmed,
                    'new_deaths' => $country->new_deaths,
                ]));
            }
        });
    }

    public function subscribes(){
        return $this->hasMany(Subscribe::class);
    }
}
