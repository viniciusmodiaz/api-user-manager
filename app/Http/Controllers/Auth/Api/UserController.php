<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\ChangePasswordRequest;
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

    public function confirm($token, UserRepository $userRepository){

        try {

            $user = $userRepository->one(['confirmation_token' => $token]);
                        
            if (!$user) {
                return response()->json([
                    'data' => [
                        'message' => 'Link inválido.',
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

    function changePassword(ChangePasswordRequest $request, UserRepository $userRepository){
        try {

            DB::beginTransaction();

            $userRepository->updateExist();
            
        } catch (Exception $exception) {
            return response()->json([
                'data' => [
                    'message' => $exception->getMessage(),
                ]
            ], $exception->getCode());
        }

    }
    
}
