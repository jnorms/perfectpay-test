<?php

namespace App\Domain\Integrations\Asaas\Billing;

use App\Domain\Enums\BillingTypeEnum;

class CreateCreditCardBillingAsaasIntegration extends CreateBillingAsaasIntegration
{
    protected function billingType(): BillingTypeEnum
    {
        return BillingTypeEnum::CREDIT_CARD;
    }
}