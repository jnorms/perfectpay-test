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
            'billing_type'           => [
                'required',
                Rule::enum(BillingTypeEnum::class)
            ],
            'credit_card_infos'      => 'required_if:billing_type,credit_card|array',
            'credit_card_infos.holder_name' => 'required_if:billing_type,credit_card|string',
            'credit_card_infos.number' => 'required_if:billing_type,credit_card|string',
            'credit_card_infos.expiry_month' => 'required_if:billing_type,credit_card|string|digits_between:1,12|after_or_equal:' . now(
                )->format('m'),
            'credit_card_infos.expiry_year' => 'required_if:billing_type,credit_card|string|size:4|after_or_equal:' . now(
                )->year,
            'credit_card_infos.ccv' => 'required_if:billing_type,credit_card|integer|digits_between:3,4',
        ];
    }
    
    
    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'client.name'                   => 'nome',
            'client.document'               => 'documento',
            'client.email'                  => 'email',
            'client.mobile_phone'           => 'celular',
            'client.address.public_place' => 'logradouro',
            'client.address.number'       => 'número',
            'client.address.complement'   => 'complemento',
            'client.address.neighborhood' => 'bairro',
            'client.address.postcode'     => 'cep',
            'billing_type'                    => 'forma de pagamento',
            'credit_card_infos.holder_name'   => 'nome do titular',
            'credit_card_infos.number'        => 'número do cartão',
            'credit_card_infos.expiry_month'  => 'mês de expiração',
            'credit_card_infos.expiry_year'   => 'ano de expiração',
            'credit_card_infos.ccv'           => 'cvv',
            'credit_card'           => 'cartão de crédito',
        ];
    }
    
}
