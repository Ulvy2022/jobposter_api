<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use stdClass;


class UserController extends Controller
{
    public function index()
    {
        return User::with(['jobsposter'])->get();

    }

    public function getAllUsers()
    {
        return User::with(['jobsposter'])->where('role', 'user')->get();
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

    public function register(Request $request)
    {
        $validated = $request->validateWithBag(
            'User',
            [
                'fullName' => 'required|max:20|min:2',
                'email' => 'required|email|unique:users,email',
                'password' => 'min:6',
            ]
        );
        $user = new User();
        $user->fullName = $request->fullName;
        $user->email = $request->email;
        $user->gender = $request->gender;
        $user->password = bcrypt($request->password);
        $user->subscription = $request->subscription;
        if ($user->role != null) {
            $user->role = $request->role;
        }
        $user->save();
        return response()->json(['msg' => 'success']);
    }

    public function store(Request $request)
    {
        $validated = $request->validateWithBag(
            'User',
            [
                'fullName' => 'required|max:20|min:2',
                'email' => 'required|email|unique:users,email',
                'password' => 'min:6',
            ]
        );
        $user = new User();
        $user->fullName = $request->fullName;
        $user->email = $request->email;
        if ($request->img != null) {
            $path = public_path('images');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $file = $request->file('img');
            $fileName = uniqid() . '_' . trim($file->getClientOriginalName());
            $file->move($path, $fileName);
            $user->img = asset('images/' . $fileName);
        }
        $user->gender = $request->gender;
        $user->password = bcrypt($request->password);
        if ($user->role != null) {
            $user->role = $request->role;
        }
        $user->save();
        return response()->json(['msg' => 'success']);
    }


    public function show($id)
    {
        return User::with(['jobsposter'])->where('id', $id)->get();
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validateWithBag(
            'User',
            [
                'fullName' => 'required|max:20|min:2',
                'password' => 'min:8|confirmed',
            ]
        );

        $user = User::findOrFail($id);
        $user->fullName = $request->fullName;
        $user->email = $request->email;
        $user->phoneNumber = $request->phoneNumber;
        $user->companyName = $request->companyName;
        $user->gender = $request->gender;
        $user->password = bcrypt($request->password);
        if ($request->img != null) {
            $path = public_path('images');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $file = $request->file('img');
            $fileName = uniqid() . '_' . trim($file->getClientOriginalName());
            $file->move($path, $fileName);
            $user->img = asset('images/' . $fileName);
        }
        $user->verify_code = $request->verify_code;
        $user->update();
        return response()->json(['msg' => 'updated']);
    }

    public function destroy($id)
    {
        return User::destroy($id);
    }

    public function verifyCode(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->verify_code = $request->verify_code;
        $user->update();
        return response()->json(['msg' => 'verify code updated']);
    }

    public function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user) {
            $user->password = bcrypt($request->newPassword);
            $user->update();
            return response()->json(['msg' => 'password change success']);
        }
    }

    public function getUserById($id)
    {
        return User::findOrFail($id);
    }

    public function count()
    {
        return User::all()->count();
    }

    public function setUserToAdmine($id)
    {
        $user = User::findOrFail($id);
        $user->role = "Admine";
        $user->update();
        return response()->json(['msg' => 'set user to admine success']);
    }

    public function updateImg(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('images', 's3');
            Image::make($request->file('img'))->resize(250, 250);
            $user->img = Storage::disk('s3')->url($path);
            $user->update();
            return response()->json(['msg' => $user->img]);
        }
        return response()->json(['msg' => 'failed']);

    }

    public function getUserImg($user_id)
    {
        $user = User::findOrFail($user_id);
        return Storage::disk("s3")->response('images/' . $user->img);
    }

    public function changePassword(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            if (Hash::check($request->oldPassword, $user->password)) {
                $user->password = bcrypt($request->newPassword);
                $user->update();
                return response()->json(['msg' => 'password changed']);
            }
            return response()->json(['msg' => 'failed']);

        }
    }

    public function getUserJob($id)
    {
        return User::with(['Jobsposter'])->where('id', $id)->get();
    }

    public function verifyEmail($id)
    {
        $user = User::findOrFail($id);
        $user->verified_at = date("D j M Y");
        $user->update();
        return response()->json(['mes' => 'Email have been verified']);
    }
}
