<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Redirect;

// use App\Providers\RouteServiceProvider;

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
    // protected $redirectTo = RouteServiceProvider::HOME;

    // protected $redirectTo;
    
    public function redirectTo(){

        if(Auth::user()->role == 'admin'){
            $this->redirectTo = route('admin');
            return $this->redirectTo;
        }
        else if(Auth::user()->role == 'peternak'){
            if(Auth::user()->verified_at){
                $this->redirectTo = route('peternak');
                return $this->redirectTo;
            }
            else{
                Auth::logout();
                session()->flash('failure', 'Tidak terauthorisasi - Akun Anda belum terverifikasi!');
                $this->redirectTo = route('login');
                return $this->redirectTo;
            }
        }
        
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
