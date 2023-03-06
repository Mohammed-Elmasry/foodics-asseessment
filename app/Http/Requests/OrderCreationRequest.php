<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Fluent;

class OrderCreationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            "products" => ["required", "array"],
            "products.*.product_id" => ["required", "integer"],
            "products.*.quantity" => ["required", "integer"]
        ];
    }
}
