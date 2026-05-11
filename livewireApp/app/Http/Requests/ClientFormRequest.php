<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ClientFormRequest extends FormRequest
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
                Rule::unique('clients')->ignore($uuid, 'uuid'),
            ],
            'tax_id' => [
                'required',
                Rule::unique('clients')->ignore($uuid, 'uuid'),
            ],
            'phone' => [
                'required',
                Rule::unique('clients')->ignore($uuid, 'uuid'),
            ],
            'recipient' =>  [
                'required',
                Rule::unique('clients')->ignore($uuid, 'uuid'),
            ],
            'email' =>  'required',
            //'province_id' =>  'required',
            //'address' => 'required',
           // 'country_id' =>  'required',
            //'city_id' =>  'required',
            //'complement' =>  'required',
            //'neighborhood' =>  'required',
           // 'postal_code' =>  'required',
            //'notes' =>  'required',
        ];
    }

    public function messages() {
        return [
            'name.required' => 'Campo obrigatório',
            'name.unique' => 'Já existe um registo com este nome. Escolha outro.',
            'tax_id.unique' => 'Já existe um registo com este NIF. Escolha outro.',
            'phone.unique' => 'Já existe um registo com este telefone. Escolha outro.',
            'recipient.unique' => 'Já existe um registo com este destinatário. Escolha outro.',
            'email.unique' => 'Já existe um registo com este email. Escolha outro.',            
            'tax_id.required' => 'Campo obrigatório',
            'phone.required' => 'Campo obrigatório',
            'recipient.required' => 'Campo obrigatório',
            'email.required' => 'Campo obrigatório',

            //'complement.required' => 'Campo obrigatório',
            //'province_id.required' => 'Campo obrigatório',
           // 'address.required' => 'Campo obrigatório',
           // 'country_id.required' => 'Campo obrigatório',
          // 'city_id.required' => 'Campo obrigatório',
          // 'neighborhood.required' => 'Campo obrigatório',
         //  'postal_code.required' => 'Campo obrigatório',
         // 'notes.required' => 'Campo obrigatório',
        ];
  
    }
}
