<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryFormRequest extends FormRequest
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
    // Pega o UUID da rota (supondo que sua rota seja algo como /categories/{uuid})
    $uuid = $this->route('uuid');

    return [
        'name' => [
            'required',
            Rule::unique('categories')->ignore($uuid, 'uuid'), // ignora a própria categoria
        ],
        'description' => [
            Rule::unique('categories')->ignore($uuid, 'uuid'),
        ],
    ];
}
 

     public function messages(): array
    {
        return [
            "name.required" => 'Campo obrigatório',
            "name.unique" => 'Já existe um registo com este nome. Escolha outro.',
            "description.unique" => 'Já existe um registo com esta descrição. Escolha outra.',
        ];
    }
}
