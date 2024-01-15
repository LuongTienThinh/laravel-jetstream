<?php

namespace Modules\Cart\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'cart_id' => 'required|string|max:255',
            'product_id' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'total_price' => 'required|numeric',
        ];
    }
}
