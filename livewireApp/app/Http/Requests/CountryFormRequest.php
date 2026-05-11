<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CountryFormRequest extends FormRequest
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
         $country_id = $this->route('id');
         return [
            'name' => [
            'required',
             Rule::unique('countries', 'name')->ignore($country_id) // Ignorar o nome caso já existir
            ],
            'iso_code' => [
            'required',
             Rule::unique('countries', 'iso_code')->ignore($country_id) // Igonrar o iso_code caso já existir
         ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Campo obrigatório',
            'iso_code.required' => 'Campo obrigatório',
            'name.unique' => 'Já existe um registo com este nome. Escolha outro.',
            'iso_code.unique' => 'Já existe um registo com este código ISO. Escolha outro.',
        ];
    }
}
