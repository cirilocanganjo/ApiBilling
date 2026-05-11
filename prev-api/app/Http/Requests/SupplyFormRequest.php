<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SupplyFormRequest extends FormRequest
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
                Rule::unique('supplies')->ignore($uuid, 'uuid'),
            ],
            'contact_person' => [
                'required',
                Rule::unique('supplies')->ignore($uuid, 'uuid'),
            ],
            'notes' => [
                'required',
                
            ],
            'phone' => [
                'required',
                Rule::unique('supplies')->ignore($uuid, 'uuid'),
            ],
            'email' => [
                'required',
                Rule::unique('supplies')->ignore($uuid, 'uuid'),
            ],
            'tax_id' => [
                'required',
                Rule::unique('supplies')->ignore($uuid, 'uuid'),
            ],
           // 'natural_person' => 'required',
          //  'country_id' => 'required',
           // 'province_id' => 'required',
           // 'city_id' => 'required',
           //  'address' => 'required',
           // 'complement' => 'required',
           // 'neighborhood' => 'required',
           // 'postal_code' => 'required',
        ];
    }

     public function messages(): array
    {
        return [
            'name.required' => 'Campo obrigatório',
            'tax_id.required' => 'Campo obrigatório',
            'contact_person.required' => 'Campo obrigatório',
            'notes.required' => 'Campo obrigatório',
            'phone.required' => 'Campo obrigatório',
            'email.required' => 'Campo obrigatório',
            'name.unique' => 'Já existe um registo com este nome. Escolha outro.',
            'contact_person.unique' => 'Já existe um registo com este contacto. Escolha outro.',
            'phone.unique' => 'Já existe um registo com este telefone. Escolha outro.',
            'email.unique' => 'Já existe um registo com este email. Escolha outro.',
            'tax_id.unique' => 'Já existe um registo com este NIF. Escolha outro.',

            //   'natural_person.required' => 'Campo obrigatório',
            //'country_id.required' => 'Campo obrigatório',
           // 'province_id.required' => 'Campo obrigatório',
            // 'city_id.required' => 'Campo obrigatório',
           // 'address.required' => 'Campo obrigatório',
            //'complement.required' => 'Campo obrigatório',
            //'neighborhood.required' => 'Campo obrigatório',
            //'postal_code.required' => 'Campo obrigatório',
        ];
    }
}
