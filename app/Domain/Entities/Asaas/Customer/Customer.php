<?php

namespace App\Domain\Entities\Asaas\Customer;

use App\Domain\Entities\Asaas\AsaasEntityInterface;
use App\Domain\Entities\EntityInterface;
use App\Exceptions\ValidationEntityException;
use Illuminate\Contracts\Support\Arrayable;

class Customer implements EntityInterface, AsaasEntityInterface, Arrayable
{
    protected string $name;
    protected string $document;
    protected string $email;
    protected string $mobilePhone;
    protected CustomerAddress $address;
    
    public function __construct() {}
    
    /**
     * @throws ValidationEntityException
     */
    public function create(
        string          $name,
        string          $document,
        string          $email,
        string          $mobilePhone,
        CustomerAddress $address
    ): self {
        $this->name = $name;
        $this->setDocument($document);
        $this->setEmail($email);
        $this->mobilePhone = $mobilePhone;
        $this->address = $address;
        
        return $this;
    }
    
    public function getAsaasBody(): array
    {
        return [
            "name"        => $this->getName(),
            "cpfCnpj"     => $this->getDocument(),
            "email"       => $this->getEmail(),
            "mobilePhone" => $this->getMobilePhone(),
            ...$this->getAddress()->getAsaasBody()
        ];
    }
    
    public function toArray(): array
    {
        return [
            "name"        => $this->getName(),
            "document"    => $this->getDocument(),
            "email"       => $this->getEmail(),
            "mobilePhone" => $this->getMobilePhone(),
            "address"     => $this->getAddress()->toArray()
        ];
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getDocument(): string
    {
        return $this->document;
    }
    
    public function getEmail(): string
    {
        return $this->email;
    }
    
    /**
     * @throws ValidationEntityException
     */
    public function setEmail(string $email): void
    {
        $this->validateEmail($email);
        $this->email = $email;
    }
    
    /**
     * @throws ValidationEntityException
     */
    public function setDocument(string $document): void
    {
        $this->validateDocument($document);
        $this->document = $document;
    }
    
    public function getMobilePhone(): string
    {
        return $this->mobilePhone;
    }
    
    public function getAddress(): CustomerAddress
    {
        return $this->address;
    }
    
    /**
     * @throws ValidationEntityException
     */
    protected function validateCPF(string $document): void
    {
        if (strlen($document) !== 11) {
            throw new ValidationEntityException(
                message  : 'The document is not valid.',
                entity   : $this,
                attribute: 'document'
            );
        }
        
        $this->validateCPFDigitOne($document);
        $this->validateCPFDigitTwo($document);
    }
    
    /**
     * @throws ValidationEntityException
     */
    protected function validateEmail($email): void
    {
        if ((bool)preg_match('/^\\S+@\\S+\\.\\S+$/', $email) === false) {
            throw new ValidationEntityException(
                message  : 'The email address is not valid.',
                entity   : $this,
                attribute: 'email'
            );
        }
    }
    
    /**
     * @throws ValidationEntityException
     */
    protected function validateDocument(string $document): void {
        switch (strlen($document)) {
            case 11:
                $this->validateCPF($document);
                break;
            case 14:
                $this->validateCNPJ($document);
                break;
            default:
                throw new ValidationEntityException(
                    message  : 'The document is not valid.',
                    entity   : $this,
                    attribute: 'document'
                );
        }
    }
    
    /**
     * @throws ValidationEntityException
     */
    protected function validateCPFDigitOne(string $document): void {
        $this->validateDigit(
            document: $document,
            validateDigit: 1,
            length: 8
        );
    }
    
    /**
     * @throws ValidationEntityException
     */
    protected function validateCPFDigitTwo(string $document): void {
        $this->validateDigit(
            document: $document,
            validateDigit: 2,
            length: 8
        );
    }
    
    /**
     * @throws ValidationEntityException
     */
    protected function validateCNPJ(string $document): void
    {
        if (strlen($document) !== 14) {
            throw new ValidationEntityException(
                message  : 'The document is not valid.',
                entity   : $this,
                attribute: 'document'
            );
        }
        
        $this->validateCNPJDigitOne($document);
        $this->validateCNPJDigitTwo($document);
    }
    
    /**
     * @throws ValidationEntityException
     */
    protected function validateCNPJDigitOne(string $document): void {
        $this->validateDigit(
            document: $document,
            validateDigit: 1,
            length: 11,
            multiplier: 5
        );
    }
    
    /**
     * @throws ValidationEntityException
     */
    protected function validateCNPJDigitTwo(string $document): void {
        $this->validateDigit(
            document: $document,
            validateDigit: 2,
            length: 11,
            multiplier: 6
        );
    }
    
    /**
     * @throws ValidationEntityException
     */
    protected function validateDigit(string $document, int $validateDigit, int $length, ?int $multiplier = null): void {
        if ($validateDigit !== 1 && $validateDigit !== 2) {
            throw new ValidationEntityException(
                message: 'The digit for validation is invalid.',
                entity   : $this,
                attribute: 'document'
            );
        }
        $length += $validateDigit;
        $digitsValidators = substr($document, 0, $length);
        $digitVerification = 0;
        if (is_null($multiplier)) {
            $multiplier = $length + 1;
        }
        for ($i = 0; $i < $length; $i++) {
            if ((strlen($document) === 14 && $multiplier > 9) || $multiplier < 2) {
                $multiplier = 9;
            }
            $digitVerification += (int)$digitsValidators[$i] * $multiplier;
            $multiplier--;
        }
        $documentDigit = (int)$document[$length];
        $rest = $digitVerification % 11;
        if (($rest < 2 && $documentDigit !== 0) || ($rest >= 2 && 11 - $rest !== $documentDigit)) {
            throw new ValidationEntityException(
                message  : 'The document is not valid.',
                entity   : $this,
                attribute: 'document'
            );
        }
    }
}