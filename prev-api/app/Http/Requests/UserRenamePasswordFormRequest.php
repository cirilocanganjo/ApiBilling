<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRenamePasswordFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'password' => 'required',
            "password_confirmation" => 'required|same:password',

        ];
    }

     public function messages(): array
    {
        return [
            'password.required' => 'Campo obrigatório',
            "password_confirmation.required" => 'Campo obrigatório',
            "password_confirmation.same" => 'As senhas não coincidem',
        ];
    }
}
