<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobsPoster;
use App\Models\User;
use LucasDotVin\Soulbscription\Models\Plan;
use LucasDotVin\Soulbscription\Models\FeaturePlan;
use LucasDotVin\Soulbscription\Models\Subscription;
use LucasDotVin\Soulbscription\Models\FeatureTicket;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class JobsPosterController extends Controller
{
    public function index()
    {
        return JobsPoster::where('active', 'No')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validateWithBag(
            'JobsPoster',
            [
                'user_id' => 'required',
                'job_title' => 'required|min:5|max:30|',
                'company_location' => 'required|min:5|max:50|',
                'job_type' => 'required|min:2|max:20',
                'company_name' => 'required|min:3|max:30',
                'contact_name' => 'required|min:2|max:20',
                'contact_email' => 'required',
                'company_address' => 'required',
                'job_description' => 'required|min:10|max:500',
                'job_requirement' => 'required|min:10|max:500',
            ]
        );
        // Before
// =======================================================
        $job = new JobsPoster();
        $date = date("Y-m-d");
        $job->user_id = $request->user_id;
        $job->job_title = $request->job_title;
        $job->company_address = $request->company_address;
        $job->company_location = $request->company_location;
        $job->job_type = $request->job_type;
        $job->job_closedate = $request->job_closedate;
        //job_closedate is the string, should convert it to date and then fit with format.
        $job->company_name = $request->company_name;
        $job->salary = $request->salary;
        $job->contact_name = $request->contact_name;
        $job->contact_email = $request->contact_email;
        $job->job_description = $request->job_description;
        $job->job_requirement = $request->job_requirement;
        $job->post_at = date("D j M Y");
        $job->expired_at = date('D j M Y', strtotime($date . ' + 15 days'));
        if ($job->expired_at == date("D j M Y")) {
            $job->active = "Yes";
        }

        // find user id in suscribtion table
        // =====Conditions =====
        $role = User::where('id', $request->user_id)->get();
        // Above code, use to get the whole info by id
        if ($role[0]['role'] == 'Admine') {
            $job->save();
            return response()->json(['msg' => 'job posted']);
        } else {
            $ticketID = app('App\Http\Controllers\FeatureTicketController')->show($request->user_id);
            $checkCharge = app('App\Http\Controllers\FeatureTicketController')
                ->update(
                    $ticketID->id
                );
            if ($checkCharge == 'Job posted') {
                $job->save();
                return response()->json(['msg' => 'job posted']);
            }
            return response()->json(['msg' => $checkCharge]);


        }
    }

    public function show($id)
    {
        return JobsPoster::where('user_id', $id)->get();
    }

    public function update(Request $request, $id)
    {

        $validated = $request->validateWithBag(
            'JobsPoster',
            [
                'job_title' => 'required|min:5|max:30|',
                'company_location' => 'required|min:5|max:50|',
                'job_type' => 'required|min:2|max:20',
                'company_name' => 'required|min:3|max:30',
                'contact_name' => 'required|min:2|max:20',
                'contact_email' => 'required',
                'company_address' => 'required',
                'job_description' => 'required|min:10|max:500',
                'job_requirement' => 'required|min:10|max:500',
            ]
        );
        $job = JobsPoster::findOrFail($id);
        $job->job_title = $request->job_title;
        $job->company_location = $request->company_location;
        $job->company_address = $request->company_address;
        $job->job_type = $request->job_type;
        $job->job_closedate = $request->job_closedate;
        $job->company_name = $request->company_name;
        $job->salary = $request->salary;
        $job->contact_name = $request->contact_name;
        $job->contact_email = $request->contact_email;
        $job->job_description = $request->job_description;
        $job->job_requirement = $request->job_requirement;
        $job->update();
        return response()->json(['msg' => 'updated']);
    }
    public function destroy($id)
    {
        $job = JobsPoster::findOrFail($id);
        $job->delete();
        return response()->json(['msg' => 'deleted']);
    }

    public function getJobById($id)
    {
        return JobsPoster::findOrFail($id);
    }

    public function getAllJobsTitle()
    {
        return JobsPoster::select('job_title')->distinct()->get();
    }

    public function getAllCompanyName()
    {
        return JobsPoster::select('company_name')->distinct()->get();
    }

    public function setJobToExpired()
    {
        $notExpireJob = JobsPoster::where('active', 'No')->get();
        foreach ($notExpireJob as $job) {
            if (strtotime($job['expired_at']) == strtotime(date("D j M Y"))) {
                $expiredJob = JobsPoster::findOrFail($job['id']);
                $expiredJob->active = "Yes";
                $expiredJob->update();
            }
        }
    }

    public function getSpecificJobs($id)
    {
        return JobsPoster::where('id', $id)->get();
    }

}
