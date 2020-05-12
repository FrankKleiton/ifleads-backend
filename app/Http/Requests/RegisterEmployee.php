<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterEmployee extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome' => 'required|string|between:6,60',
            'email' => 'required|email|unique:usuarios',
            'senha' => 'required|string|between:8,80',
            'role' => 'required|integer|between:2,3'
        ];
    }
}
