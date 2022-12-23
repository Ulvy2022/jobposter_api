<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    use HasApiTokens;
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        //check password
        if (!$user || !Hash::check($request->password, $user->password) || $user->verified_at == null) {
            return response()->json(['sms' => "Invalid password"]);
        }
        $token = $user->createToken('myToken')->plainTextToken;
        return response()->json($user, 200);
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');
        return response()->json(['mes' => 'Log out Successfully'])->withCookie($cookie);
    }

    public function loginViaGoogle(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        //check email
        if (!$user) {
            return response()->json(['sms' => "Invaliid password"]);
        }
        $token = $user->createToken('myToken')->plainTextToken;
        return response()->json(['token' => $token, 'message' => 'success login', 'id' => $user['id']], 200);
    }
}
