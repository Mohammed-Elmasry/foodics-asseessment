<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

    protected function failedValidation(Validator $validator)
    {
        throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY, "Invalid Request Content");
    }
}
