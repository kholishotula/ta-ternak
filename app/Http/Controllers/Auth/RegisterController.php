<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\User;
use App\GrupPeternak;
use Redirect;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    public function redirectTo(){
        Auth::logout();
        $this->redirectTo = route('login');
        return $this->redirectTo;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 
    }

    public function showRegistrationForm(){
        $grupPeternak = GrupPeternak::all();

        return view('auth.register')->with('grupPeternak', $grupPeternak);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // return Validator::make($data, [
        //     'ktp' => ['required', 'string', 'max:16'],
        //     'name' => ['required', 'string', 'max:255'],
        //     'username' => ['required', 'string', 'max:255', 'unique:users'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'password' => ['required', 'string', 'min:8', 'confirmed'],
        //     'grup_peternak' => ['required'],
        // ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function create(Request $data)
    {
        // $error = validator($data->all());

        // if($error->fails()){
        //     session()->flash('failure', $error->errors()->all());
        //     $this->redirectTo = route('login');
        //     return $this->redirectTo;
        // }

        // $rules = array(
        //     'password' => 'required|string|min:8|confirmed',
        //     'grup_peternak' => 'required',
        //     'name' => 'required',
        //     'ktp' => 'required|max:16',
        //     'username' => 'required|string|max:255|unique:users',
        //     'email' => 'required|string|email|max:255|unique:users'
        // );

        // $error = Validator::make($data->all(), $rules);

        // // dd($error->errors()->all());
        // if($error->fails()){
        //     // session()->flash('failure', $error->errors()->all());
        //     return redirect('/register')->with('failure', $error->errors()->all());
        // }

        $this->validate($data, [
            'password' => 'required|string|min:8|confirmed',
            'grup_peternak' => 'required',
            'name' => 'required',
            'ktp' => 'required|min:16|max:16',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users'
        ]);

        $user = User::create([
            'grup_id' => $data->grup_peternak,
            'ktp_user' => $data->ktp,
            'name' => $data->name,
            'username' => $data->username,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);

        return redirect('/')->with('success', 'Akun Anda berhasil terdaftar. Tunggu verifikasi dari Ketua Grup/Admin');
    }
}
