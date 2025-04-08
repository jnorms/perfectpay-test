<?php

namespace App\Domain\Integrations\Asaas;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

abstract class AsaasIntegrationsAbstract
{
    protected PendingRequest $client;
    protected Response $response;
    
    public function __construct() {
        $this->client = Http::asaas();
    }
    
    abstract public function handle(): void;

    public function getResponse(): Response
    {
        return $this->response;
    }
    
    public function getResponseBody(): array
    {
        return $this->getResponse()->json();
    }
}