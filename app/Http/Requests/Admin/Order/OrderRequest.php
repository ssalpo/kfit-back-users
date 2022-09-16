<?php

namespace App\Http\Requests\Admin\Order;

use App\Constants\Order;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'client_id' => 'required|numeric|exists:clients,id',
            'product_id' => 'required|numeric|exists:clients,id',
            'price' => 'required|numeric',
            'status' => 'required|numeric|in:' . implode(',', Order::ALL_STATUSES),
            'expired_at' => 'nullable|date_format:Y-m-d',
        ];
    }
}
