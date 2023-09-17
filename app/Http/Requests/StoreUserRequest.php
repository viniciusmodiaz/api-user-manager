<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email|max:255|',
            'password' => [
                'required', 
                'min:8', 
                'required_with:password_confirmation',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
                ,'same:password_confirmation'
            ],
            'password_confirmation' => 'required',          
        ];
    }

    public function failedValidation(Validator $validator)

    {

        throw new HttpResponseException(response()->json([

            'success'   => false,

            'message'   => 'Erro na validação',

            'data'      => $validator->errors()

        ],400));

    }

    public function messages() {
        return [
            'name.required' => 'O campo name é obrigatório',
            'password.required' => 'A password é obrigatória',
            'password.min' => 'A senha deve conter pelo menos 8 caracteres',
            'password.same' => 'Os campos confirmação de senha e senha devem ser iguais',
            'password.regex' => 'A senha deve conter pelo menos uma letra maiúscula, uma letra minúscula, um número, um caracter especial',
            'password_confirmation.min' => 'A senha deve conter pelo menos 8 caracteres',
            'email.unique' => 'Email já cadastrado!',
            'email.required' => 'O campo de email é obrigatório',
        ];
    }
}
