<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserEditRequest extends FormRequest
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
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user,
            'avatar' => 'nullable|max:255',
            'active' => 'required|boolean',
            'role' => 'required|numeric|exists:roles,id',
            'password' => 'nullable|min:6'
        ];
    }
}
