<?php

namespace App\Domain\Entities\Asaas\Customer;

use App\Domain\Entities\Asaas\AsaasEntityInterface;
use App\Domain\Entities\EntityInterface;
use Illuminate\Contracts\Support\Arrayable;

class CustomerAddress implements EntityInterface, AsaasEntityInterface, Arrayable
{
    private string $address;
    private string $number;
    private ?string $complement;
    private string $neighborhood;
    private string $postcode;
    
    public function __construct() {}
    
    /**
     * @param  string  $address
     * @param  string  $number
     * @param  string|null  $complement
     * @param  string  $neighborhood
     * @param  string  $postcode
     * @return CustomerAddress
     */
    public function create(
        string      $address,
        string      $number,
        string|null $complement,
        string      $neighborhood,
        string      $postcode,
    ): self {
        $this->address = $address;
        $this->number = $number;
        $this->complement = $complement;
        $this->neighborhood = $neighborhood;
        $this->postcode = $postcode;
        
        return $this;
    }
    
    public function getAsaasBody(): array
    {
        return [
            "address"       => $this->address,
            "addressNumber" => $this->number,
            "complement"    => $this->complement,
            "province"      => $this->neighborhood,
            "postalCode"    => $this->postcode,
        ];
    }
    
    public function toArray(): array
    {
        return [
            'address'      => $this->address,
            'number'       => $this->number,
            'complement'   => $this->complement,
            'neighborhood' => $this->neighborhood,
            'postcode'     => $this->postcode,
        ];
    }
}