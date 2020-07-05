<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Models\Subscribe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CountryController extends Controller
{
    public function index(){
        return Cache::get('countriesIndex', function(){
            $collection = Country::select(['id', 'name'])->get();
            $collection = $collection->sortByDesc(function($country){
                return intval(str_replace(',', '', $country->confirmed));
            });
            return $collection->values()->all();
        });
    }

//    public function search(Request $request){
//        $query = $request->validate([
//            'query' => 'string|required',
//        ])['query'];
//        return Country::where('name', 'LIKE', "%{$query}%")->select(['id', 'name', 'confirmed'])->get()->sortBy(function($country){
//            return intval(str_replace(',', '', $country->confirmed));
//        });
//    }

    public function get(Request $request, $id){
        $data = $this->validate($request, [
            'device_token' => 'string|nullable',
        ]);
        $country = Country::findOrFail($id);
        $country = $country->toArray();
        if($request->exists('device_token')){
            $country['notify'] = !!Subscribe::where('device_token', $data['device_token'])->where('country_id', $id)->first();
        }else{
            $country['notify'] = false;
        }
        return response()->json($country);
    }

}
