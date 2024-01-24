<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Traits\HasJsonResponse;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;


class AuthController extends Controller
{

    use HasJsonResponse;

    public function register(RegisterRequest $request)
    {
        try{
            $data['name']           = $request['name'];
            $data['email']          = $request['email'];
            $data['password']       = Hash::make($request['password']);
            $data['remember_token'] = Str::random(10);

            $user = User::create($data);

            if (!$user) {
                return $this->jsonResponse(false, 500, "Sorry we could not create account at this time. Try again later", ['errors' => ['']], false, false);
            }
            return $this->jsonResponse(true, 200, "User account created successfully", ['users' => $user], false, false);

        }catch(\Exception $e){
            $log =  $e->getMessage();
            return $this->jsonResponse(false, 500, "Sorry we could not authenticate at this time. Try again later", ['error' => $log], false, false);
        }
    }

    public function login(LoginRequest $request)
    {
        try{
            $credentials = $request->only('email', 'password');

            // Create token
            try {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return this->jsonResponse(false, 400, "Login credentials are invalid.", false, false, false);
                }
            } catch (JWTException $e) {
                // return $credentials;
                return $this->jsonResponse(false, 500, "Could not create token.", false, false, false);
            }

            //authenticate the user
            $this->user = auth()->authenticate($token);

            $email = $request->email;

            $user = User::where('email', $email)->first();

            return $this->jsonResponse(true, 200, "Login successful", ['users' => $user], $token, false);

        }catch(\Exception $e){

            $log =  $e->getMessage();
            return $this->jsonResponse(false, 500, "Sorry we could not authenticate at this time. Try again later", ['error' => $log], false, false);

        }
    }
}
