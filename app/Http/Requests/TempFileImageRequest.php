<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TempFileImageRequest extends FormRequest
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
            'model' => 'required',
            'folder' => 'required',
            'filename' => 'required',
            'width' => 'required|numeric|max:1000',
            'height' => 'required|numeric|max:1000',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'model' => $this->route('model'),
            'folder' => $this->route('folder'),
            'filename' => $this->route('filename'),
            'width' => $this->route('width'),
            'height' => $this->route('height'),
        ]);
    }
}
