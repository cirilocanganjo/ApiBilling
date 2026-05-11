<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SubCategoryFormRequest extends FormRequest
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
                Rule::unique('subcategories')->ignore($uuid, 'uuid'), // ignora a própria subcategoria
            ],
            'category_id' => [
                'required',               
            ],
        ];
    }

     public function messages(): array
    {
        return [
            'name.required' => 'Campo obrigatório',
            'category_id.required' => 'Campo obrigatório',
            'name.unique' => 'Já existe um registo com este nome. Escolha outro.',            
        ];
    }
}
