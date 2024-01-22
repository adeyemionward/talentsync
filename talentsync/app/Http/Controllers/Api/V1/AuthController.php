<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    protected $user;
   protected $result;
    public function __construct()
    {
        $this->result = (object)array(
            'status' => false,
            'status_code' => 200,
            'message' => null,
            'data' => (object) null,
            'token' => null,
            'debug' => null
        );
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users|unique:users',
            'password' => 'required|string|min:6',

        ]);

        if ($validator->fails()) {
            $this->result->status = false;
            $this->result->message = "Sorry a Validation Error Occured";
            $this->result->data->errors = $validator->errors()->all();
            $this->result->status_code = 422;
            return response()->json($this->result, 422);
        }

            $data['name'] = $request['name'];
            $data['email'] = $request['email'];
            $data['password'] = Hash::make($request['password']);
            $data['remember_token'] = Str::random(10);

            $user = User::create($data);


            if (!$user) {
                $this->result->status = false;
                $this->result->message = "Sorry we could not create account at this time. Try again later";
                $this->result->data->error = ['errors' => ['']];
                $this->result->status_code = 500;
                return response()->json($this->result);
            }

            $this->result->status = true;
            $this->result->message = "User account created successfully.";
            $this->result->data->user = $user;
            $this->result->status_code = 200;
            return response()->json($this->result, 200);

    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        // Create token
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response_data(false, 400, "Login credentials are invalid.", false, false, false);
            }
        } catch (JWTException $e) {
            // return $credentials;
            return response_data(false, 500, "Could not create token.", false, false, false);
        }

        // at this point we check if the user has 2fa

        $this->user = auth()->authenticate($token);

        $email = $request->email;

        $user = User::where('email', $email)->first();

        $this->result->status = true;
        $this->result->message = "Login successful.";
        $this->result->data->user = $user;
        $this->result->token = $token;
        $this->result->status_code = 200;
        return response()->json($this->result, 200);
    }
}
