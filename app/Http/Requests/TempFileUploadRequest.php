<?php

namespace App\Http\Requests;

use App\Constants\TempFile;
use Illuminate\Foundation\Http\FormRequest;

class TempFileUploadRequest extends FormRequest
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
            'file' . $this->markFileAsArray() => [
                'required',
                $this->allowedMimes(),
                "max:" . TempFile::MAX_FILE_SIZE
            ]
        ];
    }

    private function markFileAsArray(): string
    {
        return is_array($this->file) ? '.*' : '';
    }

    private function allowedMimes(): string
    {
        return 'mimes:' . implode(',', TempFile::ALLOW_FILE_MIME_TYPES);
    }
}
