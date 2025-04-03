<?php

namespace Tests\Feature\Integrations;

use App\Domain\Entities\Asaas\Customer\Customer;
use App\Domain\Entities\Asaas\Customer\CustomerAddress;
use App\Domain\Integrations\Asaas\CreateCustomerAsaasIntegration;
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
     * Validate that the asaas integration has bad request status code a send request without token
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
}
