<?php

namespace App\Http\Resources\V1;

use App\Domain\Integrations\Asaas\Billing\GetTicketBarCodeIntegration;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketPaymentInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $integration = app(GetTicketBarCodeIntegration::class, ['order' => $this->resource]);
        $integration->handle();
        return [
            'bar_code' => $integration->getResponseBody()['barCode'],
        ];
    }
}
