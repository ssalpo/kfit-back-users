<?php

namespace App\Http\Requests\Admin\Order;

use App\Constants\Order;
use Illuminate\Foundation\Http\FormRequest;

class OrderChangeStatusRequest extends FormRequest
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
            'status' => ['required', 'numeric', 'in:' . implode(',', Order::ALL_STATUSES)]
        ];
    }
}
