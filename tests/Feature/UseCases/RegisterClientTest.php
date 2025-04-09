<?php

namespace Feature\UseCases;

use App\Domain\UseCases\Client\RegisterClient;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class RegisterClientTest extends TestCase
{
    public function test_can_register_a_client(): void
    {
        $document = '28695290077';
        $registerClient = app(
            RegisterClient::class,
            [
                'name'         => 'Nelson Vicente Mendes',
                'document'     => $document,
                'email'        => 'nelson_vicente_mendes@madhause.com.br',
                'mobilePhone'  => '83988995231',
                'publicPlace'  => 'Rua José Ulisses de Lucena',
                'number'       => '1234',
                'complement'   => null,
                'neighborhood' => 'Mirante',
                'postcode'     => '58407688',
            ]
        );
        
        $registerClient->handle();
        $registerClient->handle();
        $client = $registerClient->getClient();
        $this->assertEquals($document, $client['document']);
        $this->assertNotNull($client['asaas_id']);
    }
}
