<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => [
                'required', 'string', 'max:255', 'email:rfc,dns,strict,spoof,filter',
                'unique:users'
            ],
            'password' => [
                'required', 'string', 'confirmed',
                'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/^\S*$/u',
            ],
            'document_number' => [
                'required', 'numeric', 'cpf_cnpj', 'unique:users'
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'document_number.cpf_cnpj' => 'The :attribute is invalid',
        ];
    }
}
