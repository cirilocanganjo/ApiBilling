<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UnitFormRequest extends FormRequest
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
        $unit_id = $this->route('id');
        return [
            'name' => [
                'required',
                Rule::unique('units')->ignore($unit_id, 'id'),
            ],
            'acronym' => [
                'required',
                Rule::unique('units')->ignore($unit_id, 'id'),
            ],
        ];
    }

     public function messages(): array
    {
        return [
            'name.required' => 'Campo obrigatório',
            'acronym.required' => 'Campo obrigatório',
            'name.unique' => 'Já existe um registo com este nome. Escolha outro.',
            'acronym.unique' => 'Já existe um registo com esta sigla. Escolha outra.',
        ];
    }
}
