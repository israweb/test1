<?php

namespace App\Http\Controllers\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Illuminate\Support\Facades\Log;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
 * Obtain the user information from provider.
 *
 * @return \Illuminate\Http\Response
 */
public function handleProviderCallback($driver)
{
    Log::debug('handleProviderCallback driver : '.$driver);
    // $driver = 'google';
    print("***********");
    try {
        $user = Socialite::driver($driver)->user();
    } catch (\Exception $e) {
        return redirect()->route('login');
    }

    $existingUser = User::where('email', $user->getEmail())->first();
    

    if ($existingUser) {
        auth()->login($existingUser, true);
    } else {
        $newUser                    = new User;
        $newUser->provider_name     = $driver;
        $newUser->provider_id       = $user->getId();
        $newUser->name              = $user->getName();
        $newUser->email             = $user->getEmail();
        $newUser->email_verified_at = now();
        $newUser->avatar            = $user->getAvatar();
        $newUser->save();

        auth()->login($newUser, true);
    }

    return redirect($this->redirectPath());
}
/**
 * Redirect the user to the provider authentication page.
 *
 * @return \Illuminate\Http\Response
 */
public function redirectToProvider($driver)
{
    $driver = $driver ?? null;
    Log::debug('driver : '.$driver);
    Log::debug('This is  redirectToProvider().');
    return Socialite::driver($driver)->redirect();
}
}
