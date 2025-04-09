<?php

namespace App\Http\Resources\V1;

use App\Domain\Enums\BillingTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreateOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid'       => $this->uuid,
            'total'      => $this->total / 100,
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'payment_infos' => [
                'bar_code' => $this->when($this->billing_type === BillingTypeEnum::BOLETO, TicketPaymentInfoResource::make($this->resource)),
                'pix' => $this->when($this->billing_type === BillingTypeEnum::PIX, PixPaymentInfoResource::make($this->resource)),
            ]
        ];
    }
}
