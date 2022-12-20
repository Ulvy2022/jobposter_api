<?php

namespace App\Http\Controllers;

use LucasDotVin\Soulbscription\Enums\PeriodicityType;
use LucasDotVin\Soulbscription\Models\Feature;

use Illuminate\Http\Request;

class FeaturesController extends Controller
{

    public function index()
    {
        return Feature::all();
    }


    public function store(Request $request)
    {
        $feature = new Feature();
        $feature->name = $request->name;
        $feature->postpaid = $request->price;
        $feature->quota = $request->quota;
        $feature->consumable = $request->consumable;
        $feature->periodicity_type = PeriodicityType::Month;
        $feature->periodicity = $request->periodicity;
        $feature->save();
        return response()->json(['msg' => 'success']);
    }


    public function show($id)
    {
        return Feature::findOrFail($id);
    }


    public function update(Request $request, Feature $Feature)
    {
        //
    }


    public function destroy(Feature $Feature)
    {
        //
    }

    public function getChargeByName($name)
    {
        $feature = Feature::where("name", $name)->get();
        if ($feature[0]['name'] == 'trail') {
            return 1;
        } else if ($feature[0]['name'] == 'silver') {
            return 3;
        } else if ($feature[0]['name'] == 'gold') {
            return 5;
        } else if ($feature[0]['name'] == 'silver') {
            return 7;
        } else if ($feature[0]['name'] == 'diamond') {
            return 1000000000;
        }
    }

    public function getFeatureId($name)
    {
        $feature = Feature::where("name", $name)->get();
        return $feature[0]['id'];
    }

    public function getNameByFeatureId($id)
    {
        return Feature::findOrFail($id);
    }

    public function dateToRestoreCharge($name)
    {
        return 15;
    }

    public function getChargeByFeatureId($id)
    {

    }
}
