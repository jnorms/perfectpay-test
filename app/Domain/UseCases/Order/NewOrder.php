<?php

namespace App\Domain\UseCases\Order;

use App\Domain\Entities\Asaas\Customer\Customer;
use App\Domain\Enums\BillingTypeEnum;
use App\Domain\Integrations\Asaas\Billing\CreateCreditCardBillingAsaasIntegration;
use App\Domain\Integrations\Asaas\Billing\CreatePixBillingAsaasIntegration;
use App\Domain\Integrations\Asaas\Billing\CreateTicketBillingAsaasIntegration;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;

class NewOrder
{
    protected array $products = [];
    protected Client $client;
    protected Order $order;
    
    public function __construct(
        protected int             $clientId,
        protected BillingTypeEnum $billingType,
    ) {}
    
    public function handle(): void
    {
        $integration = match ($this->billingType) {
            BillingTypeEnum::BOLETO => CreateTicketBillingAsaasIntegration::class,
            BillingTypeEnum::PIX => CreatePixBillingAsaasIntegration::class,
            BillingTypeEnum::CREDIT_CARD => CreateCreditCardBillingAsaasIntegration::class,
        };
        
        $order = $this->createOrder();
        
        $integration = app(
            $integration,
            [
                'customer' => $this->makeCustomer(),
                'orderId'  => $order->uuid,
                'total'    => $order->total,
            ]
        );
        $integration->handle();
        $order->update(['asaas_id' => $integration->getResponseBody()['id']]);
    }
    
    protected function makeCustomer(): Customer
    {
        $customer = new Customer();
        $customer->setAsaasId($this->findClient()->asaas_id);
        return $customer;
    }
    
    protected function findClient(): Client
    {
        if (isset($this->client) === false) {
            $this->client = Client::query()->findOrFail($this->clientId);
        }
        return $this->client;
    }
    
    public function setProduct(string $product, int $amount): void
    {
        $this->products[$product] = $amount;
    }
    
    protected function createOrder(): Order
    {
        $total = 0;
        foreach ($this->products as $product => $amount) {
            $total += $this->findProduct($product)->price * $amount;
        }
        $this->order = $this->findClient()->orders()->create(
            [
                'total' => $total,
                'billing_type' => $this->billingType,
            ]
        );
        
        return $this->order;
    }
    
    protected function findProduct(string $product): Product
    {
        return Product::query()->whereUuid($product)->firstOrFail();
    }
    
    public function getOrder(): Order
    {
        return $this->order;
    }
}