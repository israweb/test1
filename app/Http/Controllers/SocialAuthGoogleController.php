<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 
use App\User;


use Exception;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthGoogleController extends Controller
{



public function redirect()
{
return Socialite::driver('google')->redirect();
}

public function callback()
{
try {
 
$googleUser = Socialite::driver('google')->user();
// print_r($googleUser);
// die();
$existUser = User::where('email',$googleUser->email)->first();
 
if($existUser) {
// Auth::loginUsingId($existUser->id);
// print_r($existUser->id);
// die();
Auth::loginUsingId($existUser->id, true);
}
else {
$user = new User;
$user->name = $googleUser->name;
$user->email = $googleUser->email;
$user->google_id = $googleUser->id;
$user->password = md5(rand(1,10000));
$user->save();
Auth::loginUsingId($user->id, true);
}
return redirect()->to('/home');
}
catch (Exception $e) {
return 'error';
}
}
}