<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(StoreUserRequest $request, User $user)
    {

        if(!$user = $user->create([
            'name'=> $request['name'],
            'email'=> $request['email'],
            'password'=> Hash::make($request['password']),
        ])) 

        abort(500, 'Error to create a new user...');

        return response()->json([
                            'data' => [
                                'user' => $user,
                                'message' => "Usuário criado com sucesso, por favor realizar confirmação via email!"
                            ]
                        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if(!auth()->attempt($credentials)) 
            abort(401, 'Invalid Credentials');
        $token = auth()->user()->createToken('auth_token');

        return response()
                        ->json([
                            'data' => [
                                'token' => $token->plainTextToken,
                            ]
                        ]);
    }
}
