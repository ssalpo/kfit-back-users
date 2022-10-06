<?php

namespace App\Http\Requests;

use App\Constants\GoodsType;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'title' => 'required|min:3|max:255',
            'description' => 'nullable',
            'price' => 'nullable|numeric',
            'expired_at' => 'nullable|date_format:Y-m-d',
            'goods' => 'nullable|array',
            'goods.*.related_id' => 'numeric',
            'goods.*.related_type' => 'numeric|in:' . implode(',', GoodsType::ALL)
        ];
    }
}
