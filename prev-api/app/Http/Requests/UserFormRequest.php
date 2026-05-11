<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
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
    public function rules() : array
    {
        $uuid = $this->route('uuid');
        return [
            
            'name' => [
                'required',                
            ],
            'email' => [
                'required',                
                Rule::unique('users')->ignore($uuid, 'uuid'),
            ] ,                          
            'type' => 'required', 

        ];
    }

    public function messages() : array
     {
        return [
            'name.required' => 'Campo obrigatório',
            'email.required' => 'Campo obrigatório',
            'type.required' => 'Campo obrigatório',
            'email.unique' => 'Já existe um registo com este email. Escolha outro.',
        ];
    }

 
}
