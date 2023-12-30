<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Request body error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create(
            $validator->validated()
        );

        return response()->json([
            'message' => "Register new user success",
            'user' => new UserResource($user)
        ]);
    }

    function login(Request $request)
    {
        $credentials = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($credentials->fails()) {
            return response()->json([
                'message' => 'Request body error',
                'errors' => $credentials->errors()
            ], 422);
        }

        if (Auth::attempt($credentials->validated())) {
            // return "berhasil";

            $user = User::where('id', Auth::user()->id)->first();
            $token = $user->createToken('token');
            return response()->json([
                'message' => 'Login success',
                'token' => $token->plainTextToken
            ]);
        } else {
            return response()->json([
                'message' => "Invalid credentials"
            ], 401);
        }
    }

    function user(Request $request)
    {
        return new UserResource(User::find($request->user()->id));
    }

    function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout success detail.'
        ]);
    }
}
