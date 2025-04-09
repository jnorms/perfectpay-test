<?php

namespace App\Http\Requests\V1;

use App\Domain\Enums\BillingTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client'                      => 'required|array',
            'client.name'                 => 'required|string',
            'client.document'             => 'required|string|between:11,14',
            'client.email'                => 'required|email',
            'client.mobile_phone'         => 'required|string|size:11',
            'client.address'              => 'required|array',
            'client.address.public_place'        => 'required|string',
            'client.address.number'       => 'required|string',
            'client.address.complement'   => 'nullable|string',
            'client.address.neighborhood' => 'required|string',
            'client.address.postcode'     => 'required|string|size:8',
            'products'                      => 'required|array',
            'products.*.product_id'  => 'required|uuid|exists:products,uuid',
            'products.*.amount'      => 'required|integer',
            'billing_type'           => [
                'required',
                Rule::enum(BillingTypeEnum::class)
            ]
        ];
    }
}
