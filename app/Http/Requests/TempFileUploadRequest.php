<?php

namespace App\Http\Requests;

use App\Models\TemporaryFile;
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
                "max:" . TemporaryFile::MAX_FILE_SIZE
            ]
        ];
    }

    private function markFileAsArray(): string
    {
        return is_array($this->file) ? '.*' : '';
    }

    private function allowedMimes(): string
    {
        return 'mimes:' . implode(',', TemporaryFile::ALLOW_FILE_MIME_TYPES);
    }
}
