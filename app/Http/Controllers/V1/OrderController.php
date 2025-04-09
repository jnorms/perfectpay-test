<?php

namespace App\Http\Controllers\V1;

use App\Domain\Enums\BillingTypeEnum;
use App\Domain\UseCases\Client\RegisterClient;
use App\Domain\UseCases\Order\NewOrder;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CreateOrderRequest;
use App\Http\Resources\V1\CreateOrderResource;

class OrderController extends Controller
{
    public function create(CreateOrderRequest $request)
    {
        $data = $request->validated();
        $client = app(
            RegisterClient::class,
            [
                'name'         => $data['client']['name'],
                'document'     => $data['client']['document'],
                'email'        => $data['client']['email'],
                'mobilePhone'  => $data['client']['mobile_phone'],
                'publicPlace'  => $data['client']['address']['public_place'],
                'number'       => $data['client']['address']['number'],
                'complement'   => $data['client']['address']['complement'],
                'neighborhood' => $data['client']['address']['neighborhood'],
                'postcode'     => $data['client']['address']['postcode'],
            ]
        );
        $client->handle();
        $order = app(
            NewOrder::class,
            [
                'clientId'    => $client->getClient()['id'],
                'billingType' => BillingTypeEnum::from($data['billing_type']),
            ]
        );
        foreach ($data['products'] as $product) {
            $order->setProduct($product['product_id'], $product['amount']);
        }
        $order->handle();
        return response(CreateOrderResource::make($order->getOrder()));
    }
}
