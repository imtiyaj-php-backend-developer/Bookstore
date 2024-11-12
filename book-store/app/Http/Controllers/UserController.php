<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    protected $statusCodeSuccess = 200;
    protected $statusCodeError = 400;
    protected $statusCodeValidation = 401;

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
        try{
            $usercheck = User::where('email',$request->email)->first();
            if($usercheck != null){
                return response([
                    'statusCode' => $this->statusCodeValidation,
                    'message' => 'This email id already register',
                    'status' => 'failed',
                    'data' => $usercheck
                ], $this->statusCodeValidation);
            }else{
                $user = User::create([
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
                $token = JWTAuth::fromUser($user);
                if($user != null){
                    return response([
                        'statusCode' => $this->statusCodeSuccess,
                        'message' => 'User registered successfully',
                        'status' => 'success',
                        'data' => $user,
                        'token' => $token,
                    ], $this->statusCodeSuccess);
                }else{
                    return response([
                        'statusCode' => $this->statusCodeValidation,
                        'message' => 'User not registered!',
                        'status' => 'failed',
                        'data' => []
                    ], $this->statusCodeValidation);
                }
            }
           
        } catch (\Exception $exception) {
            dd($exception);
            return response([
                'statusCode' => $this->statusCodeError,
                'message' => 'Something went wrong',
                'status' => 'error',
                'data' => []

            ], $this->statusCodeError);
        }

    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                $token = JWTAuth::fromUser($user);

                return response()->json([
                    'statusCode' => 200,
                    'message' => 'Login successfully',
                    'data' => [
                        "id" => $user->id,
                        "username" => $user->username,
                        "email" => $user->email,
                        "token" => $token
                    ]
                ], 200);
            } else {
                return response()->json([
                    'statusCode' => 401,
                    'message' => 'Invalid credentials',
                    'data' => []
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Something went wrong',
                'status' => 'error',
                'data' => []
            ], 500);
        }
    }

}
