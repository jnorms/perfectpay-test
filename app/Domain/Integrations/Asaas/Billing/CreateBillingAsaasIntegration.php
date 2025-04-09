<?php

namespace App\Domain\Integrations\Asaas\Billing;

use App\Domain\Entities\Asaas\Customer\Customer;
use App\Domain\Enums\BillingTypeEnum;
use App\Domain\Integrations\Asaas\AsaasIntegrationsAbstract;
use Illuminate\Http\Client\ConnectionException;

abstract class CreateBillingAsaasIntegration extends AsaasIntegrationsAbstract
{
    public function __construct(
        protected Customer $customer,
        protected string $orderId,
        protected int $total,
    ) {
        parent::__construct();
    }
    
    /**
     * @throws ConnectionException
     */
    public function handle(): void
    {
        $this->response = $this->client->post(
            'payments',
            [
                "customer"                                   => $this->customer->getAsaasId(),
                "billingType"                                => $this->billingType()->name,
                "value"                                      => $this->total / 100,
                "dueDate"                                    => now()->addDay(),
                "description"                                => "Pedido " . $this->orderId,
                "daysAfterDueDateToRegistrationCancellation" => 1,
                "externalReference"                          => $this->orderId,
            ]
        );
    }
    
    abstract protected function billingType(): BillingTypeEnum;
}