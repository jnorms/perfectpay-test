<?php

namespace App\Domain\Integrations\Asaas\Billing;

use App\Domain\Entities\Asaas\Customer\Customer;
use App\Domain\Enums\BillingTypeEnum;

class CreateCreditCardBillingAsaasIntegration extends CreateBillingAsaasIntegration
{
    
    public function __construct(
        protected Customer $customer,
        protected string $orderId,
        protected int $total,
        protected array $additionalData
    ) {
        parent::__construct($customer, $orderId, $total);
    }
    protected function billingType(): BillingTypeEnum
    {
        return BillingTypeEnum::CREDIT_CARD;
    }
    
    protected function getAsaasData(): array {
        $address =  $this->customer->getAddress()->toArray();
        
        return [
            ...parent::getAsaasData(),
            "creditCard" => [
                "holderName"  => $this->additionalData['holder_name'],
                "number"      => $this->additionalData['number'],
                "expiryMonth" => $this->additionalData['expiry_month'],
                "expiryYear"  => $this->additionalData['expiry_year'],
                "ccv"         => $this->additionalData['ccv'],
            ],
            "creditCardHolderInfo" => [
                "name" => $this->customer->getName(),
                "email" => $this->customer->getEmail(),
                "cpfCnpj" => $this->customer->getDocument(),
                "postalCode" => $address['postcode'],
                "addressNumber" => $address['number'],
                "addressComplement" => $address['complement'],
                "mobilePhone" => $this->customer->getMobilePhone()
              ],
        ];
    }
}