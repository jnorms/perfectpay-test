<?php

namespace App\Domain\Integrations\Asaas\Billing;

use App\Domain\Enums\BillingTypeEnum;

class CreateTicketBillingAsaasIntegration extends CreateBillingAsaasIntegration
{
    protected function billingType(): BillingTypeEnum
    {
        return BillingTypeEnum::BOLETO;
    }
}