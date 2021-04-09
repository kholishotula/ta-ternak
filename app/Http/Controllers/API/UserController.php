<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 
use Symfony\Component\HttpFoundation\Response;
use App\User; 
use Validator;

class UserController extends Controller
{
    CONST HTTP_OK = Response::HTTP_OK;
  	CONST HTTP_CREATED = Response::HTTP_CREATED;
	CONST HTTP_UNAUTHORIZED = Response::HTTP_UNAUTHORIZED;

	public function login(Request $request){ 
	    $credentials = [
	        'email' => $request->email, 
	        'password' => $request->password
	    ];

	    if(Auth::attempt($credentials)){ 
	     	$user = Auth::user(); 
	      	$token['token'] = $this->get_user_token($user, "appToken");
	      	$response = self::HTTP_OK;

	    	return $this->get_http_response("success", $token, $response);
	    }
	    else { 
	      	$error = "Unauthorized Access";
	      	$response = self::HTTP_UNAUTHORIZED;

	      	return $this->get_http_response("error", $error, $response);
	    } 
	}


	public function register(Request $request){ 
    	$validator = Validator::make($request->all(), [ 
	      	'name' => 'required', 
	      	'username' => 'required|unique:users', 
	      	'email' => 'required|email|unique:users', 
	      	'password' => 'required|min:8', 
	      	'password_confirmation' => 'required|same:password', 
    	]);

	    if($validator->fails()){ 
	      	return response()->json([
	      		'status' => 'error',
	      		'error'=> $validator->errors()
	      	]);
	    }

	    $data = $request->all(); 
	    $data['password'] = Hash::make($data['password']);

	    $user = User::create($data); 

	    $success['token'] = $this->get_user_token($user, "appToken");
	    $success['name'] = $user->name;
	    $response = self::HTTP_CREATED;

	    return $this->get_http_response("success", $success, $response);
  	}


  	public function logout(Request $res){
    	if (Auth::user()) {
        	$user = Auth::user()->token();
        	$user->revoke();

        	return $this->get_http_response("success", "Logout successfully", self::HTTP_OK);
      	}
      	else {
      		return $this->get_http_response("error", "Unable to logout", self::HTTP_UNAUTHORIZED);
    	}
   	}


  	public function get_user_details_info() { 
	    $user = Auth::user(); 
	    $response = self::HTTP_OK;

	    return $user ? $this->get_http_response("success", $user, $response) : $this->get_http_response("Unauthenticated user", $user, $response);
  	}


  	public function get_http_response(string $status = null, $data = null, $response){
	    return response()->json([
	        'status' => $status, 
	        'data' => $data,
	    ], $response);
  	}


  	public function get_user_token($user, string $token_name = null) {
     	return $user->createToken($token_name)->accessToken; 
  	} 

}
