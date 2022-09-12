<?php

namespace App\Http\Requests;

class CategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'type' =>'nullable|max:255',
            'status'=> 'required|numeric',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     * Custom validation message
     */
    public function messages(): array
    {
        return [
            'name.required'  => 'Please give category name',
            'name.max'       => 'Please give category name maximum of 255 characters',
            'type.max' => 'Please give category type characters',
            'status.required'  => 'Please give category status',
            'status.numeric'   => 'Please give a numeric category status',
        ];
    }
}
