<?php

namespace App\Http\Controllers;


use LucasDotVin\Soulbscription\Models\Subscription;
use LucasDotVin\Soulbscription\Models\FeatureTicket;
use LucasDotVin\Soulbscription\Models\FeaturePlan;

use LucasDotVin\Soulbscription\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{

    public function index()
    {
        return Subscription::get();
    }


    public function store(Request $request)
    {
        $student = User::find($request->subscriber_id);
        $plan = Plan::find($request->plan_id);
        $student->subscribeTo($plan, expiration: today()->addMonth(), startDate: null);
        $student->subscription = $plan['name'];
        $student->update();
        $this->deActiveSub($request->subscriber_id);
        $feature_id = app('App\Http\Controllers\FeaturesController')->getFeatureId($plan['name']);
        $plan_id = app('App\Http\Controllers\PlaneController')->getPlanId($plan['name']);

        $charge = app('App\Http\Controllers\FeaturesController')->getChargeByName($plan['name']);

        app('App\Http\Controllers\FeaturePlanController')->store($feature_id, $plan_id, $charge);
        $subscriber_id = Subscription::where("subscriber_id", $request->subscriber_id)
            ->where('was_switched', 1)->first();
        $featurePlan = FeaturePlan::where('feature_id', $feature_id)->orderBy('id')->get()->first();
        $charges = Plan::findOrFail($featurePlan['plan_id']);
        app('App\Http\Controllers\FeatureTicketController')
            ->store(
                $charges['periodicity'],
                $featurePlan['id'],
                $subscriber_id['subscriber_id'],
                $charges['name'],
            );

        return response()->json(['msg' => 'subscribe successful']);
    }

    public function show($id)
    {
        return Subscription::where('subscriber_id', $id)->get();
    }


    public function switchTo(Request $request)
    {
        $oldSub = Subscription::where('subscriber_id', $request->subscriber_id)
            ->where('was_switched', 0)->get();
        $newSub = Subscription::findOrFail($oldSub[0]['id']);
        $newSub->was_switched = true;
        $newSub->update();
        $this->store($request);

        return response()->json(['msg' => 'subscribe successful']);
    }


    public function destroy($id)
    {
        $subscriber = Subscription::findOrFail($id);
        $subscriber->canceled_at = Carbon::now()->toDayDateTimeString();
        $subscriber->save();

    }


    public function getUserPlane($id)
    {
        return User::with(['Subscribsion'])->where('id', $id)->first();
    }

    public function userTrail(Request $request)
    {
        $student = User::find($request->subscriber_id);
        $plan = Plan::find($request->plan_id);
        $student->subscribeTo($plan, expiration: today()->addMonth(), startDate: null);
        $student->subscription = $plan['name'];
        $student->update();
        $this->deActiveSub($request->subscriber_id);
        $feature_id = app('App\Http\Controllers\FeaturesController')->getFeatureId($plan['name']);
        $plan_id = app('App\Http\Controllers\PlaneController')->getPlanId($plan['name']);

        $charge = app('App\Http\Controllers\FeaturesController')->getChargeByName($plan['name']);

        app('App\Http\Controllers\FeaturePlanController')->store($feature_id, $plan_id, $charge);
        $subscriber_id = Subscription::where("subscriber_id", $request->subscriber_id)
            ->where('was_switched', 1)->first();
        $featurePlan = FeaturePlan::where('feature_id', $feature_id)->orderBy('id')->get()->first();
        $charges = Plan::findOrFail($featurePlan['plan_id']);
        app('App\Http\Controllers\FeatureTicketController')
            ->store(
                $charges['periodicity'],
                $featurePlan['id'],
                $subscriber_id['subscriber_id'],
                $charges['name'],
            );

        return response()->json(['msg' => 'subscribe successful']);

    }

    public function getCostOfSub($name)
    {
        if ($name == "Trailer") {
            return 0;
        } else if ($name == "Silver") {
            return 20;
        } else if ($name == "Gold") {
            return 40;
        } else if ($name == "Diamond") {
            return 60;
        }
    }

    public function setChargeBySubName($name)
    {
        if ($name == 'Trailer') {
            return 1;
        } else if ($name == 'Silver') {
            return 3;
        } else if ($name == 'Gold') {
            return 6;
        } else if ($name == 'Diamond') {
            return 1000000000;
        }
    }

    public function addDays($sub_id)
    {
        $sub = Subscription::findOrFail($sub_id);
        $sub->expired_at = $sub->expired_at->addDays(30 + $sub->expired_at->format('d'));
        $sub->update();
        return response()->json(['smg' => 'renewal successfully']);
    }

    public function setSubToExpired()
    {
        $sub_id = Subscription::where("active", 1)->get();
        foreach ($sub_id as $item) {
            $sub_update = Subscription::findOrFail($item['id']);
            if (date('Y-m-d', strtotime($sub_update->expired_at)) == date('Y-m-d')) {
                $sub_update->active = 0;
                $sub_update->update();
            }
        }
    }

    public function cancelSubscribe(Request $request)
    {
        $sub_update = Subscription::findOrFail($request->sub_id);
        $sub_update->canceled_at = Carbon::now();
        $sub_update->active = 0;
        return response()->json(['msg' => "cancel successfully"]);
    }

    public function deActiveSub($id)
    {
        $subCount = count($this->show($id));
        $subs = $this->show($id);
        if ($subCount > 1) {
            foreach ($subs as $key => $value) {
                if ($key < $subCount - 1) {
                    $subUpdate = Subscription::findOrFail($value->id);
                    $subUpdate->active = 0;
                    $subUpdate->update();
                }
            }
        }
    }

    public function user_current_scubscribe($id)
    {
        $current_sub = Subscription::where('active', 1)->where('subscriber_id', $id)->get();
        if (count($current_sub) > 0) {
            return $current_sub[0]->active;
        }
        return 0;
    }

}
