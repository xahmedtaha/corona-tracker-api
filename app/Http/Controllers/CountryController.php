<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(){
        $collection = Country::select(['id', 'name', 'confirmed'])->get();
        $collection = $collection->sortByDesc(function($country){
            return intval(str_replace(',', '', $country->confirmed));
        });
        return $collection->values()->all();
    }

    public function search(Request $request){
        $query = $request->validate([
            'query' => 'string|required',
        ])['query'];
        return Country::where('name', 'LIKE', "%{$query}%")->select(['id', 'name', 'confirmed'])->get()->sortBy(function($country){
            return intval(str_replace(',', '', $country->confirmed));
        });
    }

    public function get($id){
        return Country::findOrFail($id);
    }
}
