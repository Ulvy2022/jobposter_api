<?php

namespace App\Http\Controllers;

use App\Mail\sendVerifyCode;
use App\Mail\setUserToAdmine;
use App\Mail\mailToNotifyUserSub;
use App\Mail\sendCV;
use App\Mail\registerMail;
use App\Models\JobsPoster;
use App\Models\User;
use LucasDotVin\Soulbscription\Models\Plan;
use Carbon\Carbon;
use LucasDotVin\Soulbscription\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Mail\DemoMail;

class MailController extends Controller
{
    public function sendVerifyCode($email)
    {
        $user = User::where('email', $email)->first();
        $username = $user['firstName'] . ' ' . $user['lastName'];
        $email = $user['email'];
        $characters = '0123456789';
        $psw = '';
        for ($i = 0; $i < 4; $i++) {
            $psw .= $characters[rand(0, strlen($characters) - 1)];
        }
        $body = [
            'username' => $username,
            'code' => $psw
        ];
        if ($user) {
            Mail::to($email)->send(new sendVerifyCode($body)); //file in Mail folder
            $user->verify_code = $psw;
            $user->update();
            return response()->json(['message' => 'success', 'code' => $psw]);
        } else {
            return response()->json(['message' => 'email not found!']);
        }
    }

    public function setUserToAdmine($email)
    {
        $user = User::where('email', $email)->first();
        $username = $user['firstName'] . ' ' . $user['lastName'];
        $email = $user['email'];
        $body = [
            'username' => $username,
        ];
        if ($user) {
            $user->role = "Admine";
            Mail::to($email)->send(new setUserToAdmine($body)); //file in Mail folder
            $user->update();
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['message' => 'email not found!']);
        }
    }



    public function registerEmail($email)
    {
        $user = User::where('email', $email)->first();
        $username = $user['fullName'];
        $email = $user['email'];
        $id = $user['id'];

        $body = [
            'username' => $username,
            'email' => $email,
            'id' => $id
        ];

        if ($user) {
            Mail::to($email)->send(new registerMail($body));
            return response()->json(['message' => 'Register Successfully']);
        } else {
            return response()->json(['message' => 'Unsuccessful']);
        }
    }

    public function mailToNotifyUserSub()
    {
        $allSubscribers = Subscription::where("active", 1)->get();
        foreach ($allSubscribers as $sub) {
            $user = User::findOrFail($sub['subscriber_id']);
            if (date('Y-m-d', strtotime($sub['created_at'])) == date('Y-m-d', strtotime($sub->expired_at->addDays(-7)))) {
                $plan = Plan::findOrFail($sub->plan_id);
                $body = [
                    'username' => $user['fullName'],
                    'sub' => date('D M j Y', strtotime($sub['created_at'])),
                    'expire' => date('D M j Y', strtotime($sub['expired_at'])),
                    'email' => $user['email'],
                    'plan' => $plan['name'],
                ];
                Mail::to($user->email)->send(new mailToNotifyUserSub($body));

                return date('Y-m-d') . date('Y-m-d', strtotime($sub->expired_at->addDays(-7)));
            }
            return $sub;
        }
    }

    public function sendCv($jobs_poster_id, $email)
    {
        $job = JobsPoster::findOrFail($jobs_poster_id)->first();
        if ($email != null) {
            Mail::to($email)->send(new sendCV()); //file in Mail folder
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['message' => 'email not found!']);
        }
    }
}
