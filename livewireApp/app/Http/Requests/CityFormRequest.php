<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CityFormRequest extends FormRequest
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
         $city_uuid = $this->route('uuid');
         return [
            'province_id' => 'required',
            'name' =>  [
            'required',
             Rule::unique('cities', 'name')->ignore($city_uuid, 'uuid') // Ignorar o nome caso já existir
            ],
            'iso_code' => [
            'required',
             Rule::unique('cities', 'iso_code')->ignore($city_uuid, 'uuid') // Ignorar o iso_code caso já existir
         ],
        ];
    }

    public function messages(): array
    {
        return [
            'province_id.required' => 'Campo obrigatório',
            'name.required' => 'Campo obrigatório',
            'name.unique' => 'Já existe um registo com este nome. Escolha outro.',
            'iso_code.required' => 'Campo obrigatório',
            'iso_code.unique' => 'Já existe um registo com este código ISO. Escolha outro.',
        ];
    }
}
