<?php

namespace Tests\Unit\Asaas\Domain\Entities\Customer;

use App\Domain\Entities\Asaas\Customer\Customer;
use App\Domain\Entities\Asaas\Customer\CustomerAddress;
use App\Exceptions\ValidationEntityException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    /**
     * Validates if a valid email passed to the entity not generates a validation exception
     * @throws Exception
     */
    public function test_can_put_an_valid_email(): void
    {
        $customer = new Customer();
        $email = 'nelson_vicente_mendes@madhause.com.br';
        $customer->create(
            name    : 'Nelson Vicente Mendes',
            document: '28695290077',
            email: $email,
            mobilePhone: '83988995231',
            address: $this->createMock(CustomerAddress::class)
        );
        $this->assertEquals($email, $customer->getEmail());
    }
    
    /**
     * Validates if an invalid email passed to the entity generates a validation exception
     * @throws Exception
     */
    public function test_cannot_put_an_invalid_email(): void
    {
        try {
            $customer = new Customer();
            $customer->create(
                name    : 'Nelson Vicente Mendes',
                document: '28695290077',
                email: 'nelson_vicente_mendesmadhause.com.br',
                mobilePhone: '83988995231',
                address: $this->createMock(CustomerAddress::class)
            );
            $this->fail('The exception should have been thrown.');
        } catch (ValidationEntityException $exception) {
            $this->assertEquals('The email address is not valid.', $exception->getMessage());
        }
    }
    
    /**
     * Validates if an invalid cpf passed to the entity generates a validation exception
     * @throws Exception
     */
    public function test_cannot_put_an_invalid_cpf(): void
    {
        try {
            $customer = new Customer();
            $customer->create(
                name    : 'Nelson Vicente Mendes',
                document: '28695290078',
                email: 'nelson_vicente_mendes@madhause.com.br',
                mobilePhone: '83988995231',
                address: $this->createMock(CustomerAddress::class)
            );
            $this->fail('The exception should have been thrown.');
        } catch (ValidationEntityException $exception) {
            $this->assertEquals('The document is not valid.', $exception->getMessage());
        }
    }
    
    /**
     * Validates if an invalid document length passed to the entity generates a validation exception
     * @throws Exception
     */
    public function test_cannot_put_an_invalid_document_length(): void
    {
        try {
            $customer = new Customer();
            $customer->create(
                name    : 'Nelson Vicente Mendes',
                document: '286952900',
                email: 'nelson_vicente_mendes@madhause.com.br',
                mobilePhone: '83988995231',
                address: $this->createMock(CustomerAddress::class)
            );
            $this->fail('The exception should have been thrown.');
        } catch (ValidationEntityException $exception) {
            $this->assertEquals('The document is not valid.', $exception->getMessage());
        }
    }
    
    /**
     * Validates if an invalid document length passed to the entity generates a validation exception
     * @throws Exception
     */
    public function test_cannot_put_an_invalid_cnpj(): void
    {
        try {
            $customer = new Customer();
            $customer->create(
                name    : 'Nelson Vicente Mendes LTDA',
                document: '74410061000164',
                email: 'nelson_vicente_mendes@madhause.com.br',
                mobilePhone: '83988995231',
                address: $this->createMock(CustomerAddress::class)
            );
            $this->fail('The exception should have been thrown.');
        } catch (ValidationEntityException $exception) {
            $this->assertEquals('The document is not valid.', $exception->getMessage());
        }
    }
    
    /**
     * Validates if a valid cnpj passed to the entity not generates a validation exception
     * @throws Exception
     */
    public function test_can_put_an_valid_cnpj(): void
    {
        $customer = new Customer();
        $email = 'nelson_vicente_mendes@madhause.com.br';
        $customer->create(
            name    : 'Nelson Vicente Mendes',
            document: '74410061000163',
            email: $email,
            mobilePhone: '83988995231',
            address: $this->createMock(CustomerAddress::class)
        );
        $this->assertEquals($email, $customer->getEmail());
    }
}
