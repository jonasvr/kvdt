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
//    public function __construct()
//    {
//        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
//    }

    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout', 'getLogout']]);
    }

    public function redirectToProvider($provider)
   {
       $scopes = [
            // 'https://www.googleapis.com/auth/calendar.readonly'
          ];
      $parameters = [
          'access_type' => 'offline',
        //    'approval_prompt' => 'force',
      ];
       return Socialite::driver($provider)->scopes($scopes)->with($parameters)->redirect();
   }

    public function loginm(){
        $data = [
            'email' => $_GET['email'],
        ];
        Auth::login(User::firstOrCreate($data));
        dd('passed');
        return redirect($this->redirectPath());
    }

    public function handleProviderCallback($provider)
   {
    //notice we are not doing any validation, you should do it
        setcookie('accessToken', '', time()-3600, "/");
        // dd( Socialite::driver($provider)->user());
       $user = Socialite::driver($provider)->stateless()->user();
       // stroing data to our use table and logging them in
       $data = [
           'name' => $user->name,
           'email' => $user->email,
       ];
      Auth::login(User::firstOrCreate($data));
    //    //after login redirecting to home page
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


//    public function getLogout()
//    {
//       auth()->logout();
//    }
}

