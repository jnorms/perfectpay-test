<?php

namespace App\Domain\Integrations\Asaas\Billing;

use App\Domain\Integrations\Asaas\AsaasIntegrationsAbstract;
use App\Models\Order;

class GetPixQrCodeIntegration extends AsaasIntegrationsAbstract
{
    public function __construct(protected Order $order) {
        parent::__construct();
    }
    
    public function handle(): void
    {
        $this->response = $this->client->get('payments/' . $this->order->asaas_id . '/pixQrCode');
    }
}