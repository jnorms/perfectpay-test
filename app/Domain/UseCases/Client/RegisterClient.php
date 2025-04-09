<?php

namespace App\Domain\UseCases\Client;

use App\Domain\Entities\Asaas\Customer\Customer;
use App\Domain\Entities\Asaas\Customer\CustomerAddress;
use App\Domain\Integrations\Asaas\Customer\CreateCustomerAsaasIntegration;
use App\Exceptions\ValidationEntityException;
use App\Models\Address;
use App\Models\Client;
use Illuminate\Http\Client\ConnectionException;

class RegisterClient
{
    protected Client $client;
    protected Address $address;
    
    public function __construct(
        protected string $name,
        protected string $document,
        protected string $email,
        protected string $mobilePhone,
        protected string $publicPlace,
        protected string $number,
        protected ?string $complement,
        protected string $neighborhood,
        protected string $postcode,
    ) {}
    
    /**
     * @throws ValidationEntityException
     * @throws ConnectionException
     */
    public function handle(): void
    {
        $this->firstOrNew();
        if (is_null($this->client->asaas_id) === false) {
            return;
        }
        $this->registerAsaasId( $this->createAsaasCustomer());
    }
    
    protected function firstOrNew(): void
    {
        $this->client = Client::query()->firstOrCreate(
            [
                'document' => $this->document,
            ],
            [
                'name'         => $this->name,
                'document'     => $this->document,
                'email'        => $this->email,
                'mobile_phone' => $this->mobilePhone,
            ]
        );
        
        $this->client->addresses()->create(
            [
                'public_place' => $this->publicPlace,
                'number'       => $this->number,
                'complement'   => $this->complement,
                'neighborhood' => $this->neighborhood,
                'postcode'     => $this->postcode,
            ]
        );
    }
    
    /**
     * @throws ConnectionException
     * @throws ValidationEntityException
     */
    protected function createAsaasCustomer(): string
    {
        $customerAddress = app(CustomerAddress::class);
        $customerAddress->create(
            address     : $this->publicPlace,
            number      : $this->number,
            complement  : $this->complement,
            neighborhood: $this->neighborhood,
            postcode    : $this->postcode,
        );
        $customer = app(Customer::class);
        $customer->create(
            name       : $this->name,
            document   : $this->document,
            email      : $this->email,
            mobilePhone: $this->mobilePhone,
            address    : $customerAddress
        );
        $integration = app(CreateCustomerAsaasIntegration::class);
        $integration->setCustomer($customer);
        $integration->handle();
        return $integration->getResponseBody()['id'];
    }
    
    protected function registerAsaasId(string $asaasId): void
    {
        $this->client->update(
            [
                'asaas_id' => $asaasId,
            ]
        );
        $this->client->fresh();
    }
    
    public function getClient(): array {
        return $this->client->toArray();
    }
}