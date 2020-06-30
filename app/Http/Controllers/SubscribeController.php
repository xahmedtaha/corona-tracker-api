<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Subscribe;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function toggleSubscribtion(Request $request){
        $data = $request->validate([
            'country_id' => 'numeric|required',
            'device_token' => 'string|required',
            'device' => 'string|in:android,ios|required',
        ]);
        $country = Country::findOrFail($data['country_id']);
        $subscribe = Subscribe::where('device_token', $data['device_token'])->where('country_id', $data['country_id'])->first();
        if($subscribe){
            $subscribe->delete();
            return response()->json(['subscribed' => false]);
        }else{
            $subscribe = Subscribe::create($data);
            return response()->json(['subscribed' => true]);
        }
    }
}
