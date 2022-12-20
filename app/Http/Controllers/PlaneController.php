<?php

namespace App\Http\Controllers;

use LucasDotVin\Soulbscription\Enums\PeriodicityType;
use LucasDotVin\Soulbscription\Models\Plan;
use LucasDotVin\Soulbscription\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class PlaneController extends Controller
{

    public function index()
    {
        return Plan::all();
    }

    public function store(Request $request)
    {
        $plan = Plan::find($request->plan_id);
        $user = User::find($request->plan_id);
        $user->subscribeTo($plan, expiration: today()->addMonth());
        return response()->json(['msg' => 'subscribe successful']);
    }

    public function expiredSubscribe()
    {
        $sub = Plan::where("expired", "No")->get();
        foreach ($sub as $job) {

            if ($job['expired_at'] == date("D j M Y")) {
                $expiredSub = Plan::findOrFail($job['subscribes_id']);
                $expiredSub->expired = "Yes";
                $expiredSub->update();
            }
        }
    }

    public function restoreCharge()
    {
        $sub = Plan::all();
        foreach ($sub as $job) {
            if (($job['expired_at']) == (date("D j M Y"))) {
                $charge = Subscription::findOrFail($job['subscribes_id']);
                $charge->leftCharge = $charge->charge;
                $charge->update();
            }
        }
    }


    public function show($id)
    {
        return User::with(['plane', 'subscribsion'])->where('id', $id)->first();
    }



    public function update(Request $request, $id)
    {
        $plane = Plan::findOrFail($id);
        $plane->subscribes_id = $request->subscribes_id;
        $plane->start_date = $request->start_date;
        $plane->end_date = $request->end_date;
        $plane->update();
        return response()->json(['msg' => 'updated']);
    }


    public function destroy($id)
    {
        return Plan::destroy($id);
        // return (Subscribe::where('user_id', $id)->get());
    }

    public function getPlanId($name)
    {
        $plan = Plan::where("name", $name)->get();
        return $plan[0]['id'];
    }

    public function getPlanName($id)
    {
        $plan = Plan::where("id", $id)->get();
        return $plan[0]['name'];
    }

    public function createNewPlan(Request $request)
    {
        $new_plan = new Plan();
        $new_plan->grace_days = $request->grace_days;
        $new_plan->name = $request->name;
        $new_plan->benifits = $request->benifits;
        $new_plan->periodicity = $request->periodicity;
        $new_plan->periodicity_type = PeriodicityType::Month;
        $new_plan->save();

        $charges = $request->charges;
        app('App\Http\Controllers\FeaturesController')->store($request);
        $feature_id = app('App\Http\Controllers\FeaturesController')->getFeatureId($request->name);
        $plan_id = $this->getPlanId($request->name);
        app('App\Http\Controllers\FeaturePlanController')->store($feature_id, $plan_id, $charges);
        return response()->json(['msg' => 'plan created']);
    }

    public function getPlanBenifits()
    {
        return Plan::all('benifits')->where('benifits', '!=', null);
    }


}
