<?php

namespace Tests\Feature\Integrations;

use App\Domain\Entities\Asaas\Customer\Customer;
use App\Domain\Entities\Asaas\Customer\CustomerAddress;
use App\Domain\Integrations\Asaas\Customer\CreateCustomerAsaasIntegration;
use Illuminate\Http\Client\RequestException;
use Tests\TestCase;

class AsaasTest extends TestCase
{
    /**
     * Validate that the asaas service settings are correct
     */
    public function test_validate_assas_http_macro(): void
    {
        $request = \Http::asaas()->get('customers');
        $response = $request->json();
        $this->assertTrue($request->successful());
        $this->assertNotEmpty($response);
    }
    
    /**
     * Validate that the asaas integration has unauthorized status code a send request without token
     */
    public function test_validate_assas_http_macro_without_access_token_header(): void
    {
        try {
            \Http::asaas()->replaceHeaders(['access-token' => ''])->get('customers');
            $this->fail('The request should have been thrown.');
        } catch (RequestException $exception) {
            $this->assertTrue($exception->response->unauthorized());
        } catch (\Throwable $exception) {
            $this->fail($exception->getMessage());
        }
    }
    
    /**
     * Validate that the asaas integration has bad request status code a send request without user agent
     */
    public function test_validate_assas_http_macro_without_user_agent_header(): void
    {
        try {
            \Http::asaas()->replaceHeaders(['User-Agent' => ''])->get('customers');
            $this->fail('The request should have been thrown.');
        } catch (RequestException $exception) {
            $this->assertTrue($exception->response->badRequest());
        } catch (\Throwable $exception) {
            $this->fail($exception->getMessage());
        }
    }
    
    public function test_create_an_asaas_customer(): void
    {
        $customerAddress = $this->app->make(CustomerAddress::class);
        $customerAddress->create(
            address: $this->faker->streetAddress,
            number:   $this->faker->buildingNumber,
            complement: null,
            neighborhood: $this->faker->words(rand(1, 3), true),
            postcode: $this->faker->postcode,
        );
        $customer = $this->app->make(Customer::class);
        $customer->create(
            name    : $this->faker->name,
            document: $this->faker->randomElement(
                          [
                              $this->faker->cpf(false),
                              $this->faker->cnpj(false),
                          ]
                      ),
            email: $this->faker->email,
            mobilePhone: $this->faker->phoneNumberCleared(false),
            address: $customerAddress
        );
        $integration = $this->app->make(CreateCustomerAsaasIntegration::class);
        $integration->setCustomer($customer);
        $integration->handle();
        $response = $integration->getResponse();
        $this->assertTrue($response->successful());
        $body = $integration->getResponseBody();
        $this->assertArrayHasKey('id', $body);
        $this->assertEquals($customer->getName(), $body['name']);
        $this->assertEquals($customer->getDocument(), $body['cpfCnpj']);
        $this->assertEquals($customer->getEmail(), $body['email']);
        $this->assertEquals($customer->getMobilePhone(), $body['mobilePhone']);
    }
}
