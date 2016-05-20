<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

//Add These three required namespace

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Cookie;
use Google_Client;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    public function redirectToProvider($provider)
   {
       $scopes = [
            'https://www.googleapis.com/auth/calendar.readonly',
          ];
      $parameters = [
          'access_type' => 'offline',
        //    'approval_prompt' => 'force',
      ];
       return Socialite::driver($provider)->scopes($scopes)->with($parameters)->redirect();
   }

    public function handleProviderCallback($provider)
   {
    //notice we are not doing any validation, you should do it
        setcookie('accessToken', '', time()-3600, "/");
       $user = Socialite::driver($provider)->user();
    //    dd($user);
    //  dd($user->getToken());
       // stroing data to our use table and logging them in
       $data = [
           'name' => $user->getName(),
           'email' => $user->getEmail(),
       ];
       Auth::login(User::firstOrCreate($data));
    // $user->getToken() => zelf geschreven in vendor/socialite/src/two/user.php
    //    setcookie('accessToken', $user->getToken(), time() + (86400 * 30), "/"); // 86400 = 1 day
       //after login redirecting to home page
       return redirect($this->redirectPath());
   }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
