<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'login' => 'string|max:255|unique:users,login',
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users',
            'password' => [
                'string',
                'min:6'
            ], # estar entre 2 valores enumerados
            'status' => 'in:active,disabled'
        ];
    }
}
