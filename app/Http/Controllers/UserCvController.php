<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JobsPoster;
use App\Models\UserCv;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class UserCvController extends Controller
{

    public function index()
    {
        return UserCv::all();
    }


    public function store(Request $request)
    {
        $newCV = new UserCv();
        $newCV->jobs_poster_id = $request->jobs_poster_id;
        $newCV->user_id = $request->user_id;
        $email = User::findOrFail($request->user_id);
        if ($request->hasFile('cv')) {
            $path = $request->file('cv')->store('CoverLetter', 's3');
            $newCV->CV = Storage::disk('s3')->url($path);
            $newCV->save();
            app('App\Http\Controllers\MailController')->sendCv($request->jobs_poster_id, $email->email);
            return response()->json(['msg' => 'success']);
        }
        return response()->json(['msg' => 'failed']);
    }


    public function show($id)
    {
        return JobsPoster::with(["cv", "User"])->where('user_id', $id)->get();
    }


    public function update(Request $request, UserCv $userCv)
    {
        //
    }


    public function destroy(UserCv $userCv)
    {
        //
    }
}
