<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Subscribe extends Model
{
    use Notifiable;
    public $fillable = ['device_token', 'country_id', 'device'];
    public function country(){
        return $this->belongsTo(Country::class);
    }

    /**
     * Specifies the subscriber's FCM token
     *
     * @return string
     */
    public function routeNotificationForFcm()
    {
        return $this->device_token;
    }
}
