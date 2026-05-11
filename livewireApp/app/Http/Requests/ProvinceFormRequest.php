<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProvinceFormRequest extends FormRequest
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
         $city_id = $this->route('id');
         return [
            'country_id' => [
            'required',
                Rule::unique('provinces', 'country_id')->ignore($city_id) // Ignorar o country_id caso já existir
            ],
            'name' => [
                'required',
                Rule::unique('provinces', 'name')->ignore($city_id) // Ignorar o name caso já existir
            ],
            'iso_code' => [
            'required',
             Rule::unique('provinces', 'iso_code')->ignore($city_id) // Igonrar o iso_code caso já existir
         ],
        ];
    }

    public function messages(): array
    {
        return [
            'country_id.required' => 'Campo obrigatório',
            'name.required' => 'Campo obrigatório',
            'iso_code.required' => 'Campo obrigatório',
            'iso_code.unique' => 'Já existe um registo com este código ISO. Escolha outro.',
            'name.unique' => 'Já existe um registo com este nome. Escolha outro.',
            'country_id.unique' => 'Já existe um registo com este país. Escolha outro.',
        ];
    }
    
}
