<?php

namespace App\Http\Resources\V1;

use App\Domain\Integrations\Asaas\Billing\GetPixQrCodeIntegration;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PixPaymentInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $integration = app(GetPixQrCodeIntegration::class, ['order' => $this->resource]);
        $integration->handle();
        return [
            'qr_code' => $integration->getResponseBody()['encodedImage'],
            'copy_and_paste' => $integration->getResponseBody()['payload'],
        ];
    }
}
