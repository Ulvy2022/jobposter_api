<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LucasDotVin\Soulbscription\Models\FeatureTicket;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use LucasDotVin\Soulbscription\Models\Feature;
use LucasDotVin\Soulbscription\Models\Subscription;
use stdClass;



class FeatureTicketController extends Controller
{

    public function index()
    {
        return $this->ToObject(DB::select('SELECT * from users order by id desc '));
    }

    public function update($id)
    {
        $featurePlan = $this->ToObject(DB::select('SELECT * from feature_tickets where id =' . $id)[0]);
        $minusCharge = $featurePlan->charges - 1;
        if ($minusCharge > -1) {
            DB::update('UPDATE feature_tickets set charges =' . $minusCharge . ' where id=' . $id);
            return 'Job posted';
        }
        return "You have used all your charges. You can post job again at " . date('Y M D', strtotime($featurePlan->restoreChrage_at));
    }

    public function show($id)
    {
        return $this->ToObject(DB::select('SELECT * FROM `feature_tickets` where subscriber_id=' . $id . ' ORDER BY id desc limit 1;')[0]);
    }

    public function ToObject($Array)
    {

        // Create new stdClass object
        $object = new stdClass();

        // Use loop to convert array into
        // stdClass object
        foreach ($Array as $key => $value) {
            if (is_array($value)) {
                $value = $this->ToObject($value);
            }
            $object->$key = $value;
        }
        return $object;
    }

    public function store($charges, $feature_id, $subscriber_id, $subscriber_type)
    {
        $featurePlan = new FeatureTicket();
        $featurePlan->charges = $charges;
        $featurePlan->full_charge = $charges;
        $featurePlan->feature_id = $feature_id;
        $featurePlan->expired_at = Carbon::now()->addMonth();
        $featurePlan->subscriber_type = $subscriber_type;
        $featurePlan->subscriber_id = $subscriber_id;

        $chrageName = app('App\Http\Controllers\FeaturesController')
            ->getNameByFeatureId($feature_id);

        $dayToAdd = app('App\Http\Controllers\FeaturesController')
            ->dateToRestoreCharge($chrageName['name']);

        $featurePlan->restoreChrage_at = Carbon::now()->addDays($dayToAdd);
        $featurePlan->save();
        return response()->json(['id' => $featurePlan['id']]);

    }


    public function destroy($id)
    {
        //
    }

    public function restoreCharge()
    {
        $sub = FeatureTicket::where("expired_at", ">=", time() - (24 * 60 * 60))->get();
        foreach ($sub as $job) {
            if (date('Y-m-d', strtotime($job['restoreChrage_at'])) == date("Y-m-d")) {
                $charge = FeatureTicket::findOrFail($job['id']);
                $charge->charges = $charge->full_charge;
                $charge->update();
                // return $charge;
            }
        }
    }

    public function userSubInfo($sub_id)
    {
        return DB::select("SELECT DISTINCT subscriptions.*,
                    plans.id,
                    features.postpaid,
                    features.name,
                    feature_plan.plan_id,
                    feature_plan.feature_id,
                    feature_plan.charges
                    FROM subscriptions
                    inner  join plans on plans.id = subscriptions.plan_id and subscriptions.subscriber_id=$sub_id
                    inner join feature_plan on feature_plan.plan_id = plans.id
                    inner join features on features.id = feature_Plan.feature_id ORDER BY feature_plan.id DESC");
    }
}
