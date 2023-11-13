<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Repositories\UserRepository;
use App\Services\ServicesToken;
use App\Mail\ConfirmationEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Exception;

class AuthController extends Controller
{
    public function __construct(private UserRepository $userRepository)
    {
    }
    /**
    * @OA\Post(
    *     path="/api/auth/autenticao",
    *     summary="Realiza autenticação utilizando as credenciais de login e password do usuário para gerar um token de acesso.",
    *     tags={"Auth"},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 @OA\Property(property="email", type="string", example="teste@gmail.com"),
    *                 @OA\Property(property="password", type="string", format="password", example="password"),
    *             ),
    *         ),
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Autenticação bem-sucedida. Retorna um JSON contendo o token de acesso.",
    *         @OA\JsonContent(
    *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhVCtvaAgxKYhYuvlGpOaQU6Pu8qHs8NRXDrOOTTyW7XI"),
    *             @OA\Property(property="message", type="string", example="Autenticação realizada com sucesso!")
    *         )
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Credenciais inválidas. Não autorizado."
    *     ),
    * )
    */

    public function authentication(AuthUserRequest $request, ServicesToken $servicesToken)
    {

        try {

            $credentials = $request->all();

            if(Auth::attempt($credentials) && $this->userRepository->UserEmailVerified($credentials['email'])){

                $user = Auth::user();

                $token = $servicesToken->generateToken(['id' => $user->id]);

                return response()->json([
                    'data' => [
                        'message' => 'Autenticação realizada com sucesso!',
                        'token' => $token
                    ]
                ]);

            }else{
                return response()->json([
                    'error' => [
                        'message' => 'As credenciais fornecidas são inválidas. Por favor, verifique seu e-mail e senha.'
                    ]
                ], 400);
            }
        } catch (Exception $exception) {


            return response()->json([
                'error' => [
                    'message' => $exception->getMessage()
                ]
            ], 500);
        }
    }
    
}
