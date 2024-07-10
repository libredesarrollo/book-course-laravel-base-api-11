<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

// controlador empleado para Sanctum con el manejo de tokens
class UserController extends Controller
{
    function login(Request $request)
    {
        $validator = validator()->make(
            $request->all(),
            [
                'email' => 'required',
                'password' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $validator->valid();

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('myapptoken')->plainTextToken;
            session()->put('token', $token);
            return response()->json(
                [
                    'isLoggedIn' => true,
                    'token' => $token,
                    'user' => auth()->user(),
                ]
            );
        }

        return response()->json('The username and/or password do not match', 422);

    }

    function logout(Request $request)
    {
        if ($request->user()) {

            // auth()->user();
            // $request->user()->currentAccessToken()->delete();
            $request->user()->tokens()->delete();
        }

        session()->flush();
        return response()->json('ok');
    }

    function checkToken() {
        try{
            [$id, $token] = explode('|', request('token'));
            $tokenHash = hash('sha256', $token);

            $tokenModel = PersonalAccessToken::where('token', $tokenHash)->first();
            
            if($tokenModel->tokenable) {
                return response()->json(
                    [
                        'isLoggedIn' => true,
                        'token' => request('token'),
                        // 'user' => auth()->user(),
                    ]
                );
            }

        } catch( Exception $e){
      
        }

        return response()->json('Invalid user', 422);
    }

}
