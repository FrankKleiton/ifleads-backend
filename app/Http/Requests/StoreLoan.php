<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoan extends FormRequest
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
        // tooker_id = matrícula
        return [
            'tooker_id' => 'required|string|max:60',
            'material_id' => 'exists:materials,id',
            'material_amount' => 'integer|min:0'
        ];
    }
}
