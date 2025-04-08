<?php

namespace App\Domain\Integrations\Asaas\Customer;

use App\Domain\Entities\Asaas\Customer\Customer;
use App\Domain\Integrations\Asaas\AsaasIntegrationsAbstract;
use Illuminate\Http\Client\ConnectionException;

class CreateCustomerAsaasIntegration extends AsaasIntegrationsAbstract
{
    protected Customer $customer;
    
    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }
    
    /**
     * @throws ConnectionException
     */
    public function handle(): void
    {
        $this->response = $this->client->post('customers', $this->customer->getAsaasBody());
    }
}