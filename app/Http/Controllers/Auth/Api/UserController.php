<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Hash;
use App\Mail\ConfirmationEmail;
use Illuminate\Support\Facades\Mail;
use App\Repositories\UserRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Exception;

class UserController extends Controller
{
    public function register(StoreUserRequest $request, UserRepository $userRepository)
    {

        try {

            DB::beginTransaction();

            $user = $userRepository->create([
                'name'=> $request['name'],
                'email'=> $request['email'],
                'password'=> Hash::make($request['password']),
                'confirmation_token'=> Str::random(128)
            ]);


            Mail::to($user->email)->send(new ConfirmationEmail($user));

            DB::commit();

            return response()->json([
                                'data' => [
                                    'message' => "Usuário criado com sucesso, por favor realizar confirmação via email!"
                                ]
                            ]);
        } catch (Exception $exception) {

            DB::rollBack();

            return response()->json([
                'error' => [
                    'message' => $exception->getMessage()
                ]
            ], 500);
        }
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

    public function confirm($token, UserRepository $userRepository){

        try {

            $user = $userRepository->one(['confirmation_token' => $token]);
                        
            if (!$user) {
                return response()->json([
                    'data' => [
                        'message' => 'Link inválido, por favor solicitar novamente o link de confirmação.',
                    ]
                ], 400);
            }

            $user = $userRepository->updateExist($user, ['email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'), 'confirmation_token' => null]);

            return response()->json([
                'data' => [
                    'message' => "Confirmação realizada com sucesso!"
                ]
            ]);

        } catch (Exception $exception) {
            return response()->json([
                'data' => [
                    'message' => $exception->getMessage(),
                ]
            ], $exception->getCode());
        }
    }
    
}
