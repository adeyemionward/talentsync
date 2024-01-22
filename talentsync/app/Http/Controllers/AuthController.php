<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
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
}
