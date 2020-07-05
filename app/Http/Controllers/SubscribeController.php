<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Subscribe;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function toggleSubscribtion(Request $request, $id){
        $data = $this->validate($request, [
            'device_token' => 'string|required',
            'device' => 'string|required',
        ]);
        $country = Country::findOrFail($id);
        $subscribe = Subscribe::where('device_token', $data['device_token'])->where('country_id', $id)->first();
        if($subscribe){
            $subscribe->delete();
            return response()->json(['subscribed' => false]);
        }else{
            $data['country_id'] = $id;
            $subscribe = Subscribe::create($data);
            return response()->json(['subscribed' => true]);
        }
    }
}
