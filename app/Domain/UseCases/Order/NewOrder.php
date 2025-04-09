<?php

namespace App\Domain\UseCases\Order;

use App\Domain\Entities\Asaas\Customer\Customer;
use App\Domain\Entities\Asaas\Customer\CustomerAddress;
use App\Domain\Enums\BillingTypeEnum;
use App\Domain\Integrations\Asaas\Billing\CreateCreditCardBillingAsaasIntegration;
use App\Domain\Integrations\Asaas\Billing\CreatePixBillingAsaasIntegration;
use App\Domain\Integrations\Asaas\Billing\CreateTicketBillingAsaasIntegration;
use App\Models\Client;
use App\Models\Order;

class NewOrder
{
    protected Client $client;
    protected Order $order;
    
    public function __construct(
        protected int             $clientId,
        protected BillingTypeEnum $billingType,
        protected array $additionalData = [],
    ) {}
    
    public function handle(): void
    {
        $integration = match ($this->billingType) {
            BillingTypeEnum::BOLETO => CreateTicketBillingAsaasIntegration::class,
            BillingTypeEnum::PIX => CreatePixBillingAsaasIntegration::class,
            BillingTypeEnum::CREDIT_CARD => CreateCreditCardBillingAsaasIntegration::class,
        };
        $order = $this->createOrder();
        $integrationAttributes = [
            'customer' => $this->makeCustomer(),
            'orderId'  => $order->uuid,
            'total'    => $order->total,
        ];
        if ($this->billingType === BillingTypeEnum::CREDIT_CARD) {
            $integrationAttributes['additionalData'] = $this->additionalData;
        }
        $integration = app(
            $integration,
            $integrationAttributes
        );
        $integration->handle();
        $order->update(['asaas_id' => $integration->getResponseBody()['id']]);
    }
    
    protected function makeCustomer(): Customer
    {
        $client = $this->findClient();
        $clientAddress = $client->addresses()->latest()->first();
        $customerAddress = new CustomerAddress();
        $customerAddress->create(
            address: $clientAddress->public_place,
            number: $clientAddress->number,
            complement: $clientAddress->complement,
            neighborhood: $clientAddress->neighborhood,
            postcode: $clientAddress->postcode,
        );
        
        $customer = new Customer();
        $customer->create(
            name       : $client->name,
            document   : $client->document,
            email      : $client->email,
            mobilePhone: $client->mobile_phone,
            address    : $customerAddress
        );
        $customer->setAsaasId($client->asaas_id);
        return $customer;
    }
    
    protected function findClient(): Client
    {
        if (isset($this->client) === false) {
            $this->client = Client::query()->findOrFail($this->clientId);
        }
        return $this->client;
    }
    
    protected function createOrder(): Order
    {
        $this->order = $this->findClient()->orders()->create(
            [
                'total' => 33500,
                'billing_type' => $this->billingType,
            ]
        );
        
        return $this->order;
    }
    
    public function getOrder(): Order
    {
        return $this->order;
    }
}