<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    // Controlador que empleamos para hacer pruebas solamente con la auth SPA de Sanctum y las rutas.api
    // tambien probamos que lo podemos consumir desde las rutas de web.php
    function authenticate(Request $request) {
       
        $validator = validator()->make($request->all(),
            [
                'email' => 'required', 'email',
                'password' => 'required'
            ]
        );

        if($validator->fails()){
            //return $validator->errors();
            return response()->json($validator->errors(),422);
        }

        $credentials = $validator->valid();
        
        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            return response()->json('Successful authentication');
        }

        return response()->json('The username and/or password do not match', 422);
    }
}
