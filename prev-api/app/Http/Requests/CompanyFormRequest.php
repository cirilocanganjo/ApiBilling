<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class CompanyFormRequest extends FormRequest
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
        $uuid = $this->route('uuid');
        return [
                'name' => [
                    'required',
                    Rule::unique('companies')->ignore($uuid, 'uuid'),
                ],
                'email' => [
                    'required',
                    Rule::unique('companies')->ignore($uuid, 'uuid'),
                ],
                'nif' => [
                    'required',
                    Rule::unique('companies')->ignore($uuid, 'uuid'),
                ],
                'phone' => [
                    'required',
                    Rule::unique('companies')->ignore($uuid, 'uuid'),
                ],
                'address' => 'required',                
                'reference' => 'required', 
        ];
    }

    public function messages() {
        return [
            'name.required' => 'Campo obrigatório',
            'email.required' => 'Campo obrigatório',
            'email.unique' => 'Email já cadastrado',
            'nif.required' => 'Campo obrigatório',
            'nif.min' => 'O NIF deve ter no mínimo 15 caracteres',
            'phone.required' => 'Campo obrigatório',
            'address.required' => 'Campo obrigatório',
            'reference.required' => 'Campo obrigatório',
            'name.unique' => 'Já existe um registo com este nome. Escolha outro.',
            'nif.unique' => 'Já existe um registo com este NIF. Escolha outro.',
            'phone.unique' => 'Já existe um registo com este telefone. Escolha outro.',            
        ];
    }
}
