<?php

namespace App\Exceptions;

use App\Domain\Entities\EntityInterface;
use Exception;

class ValidationEntityException extends Exception
{
    protected ?string $entity;
    
    protected ?string $attribute;
    
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, ?EntityInterface $entity = null, ?string $attribute = null)
    {
        parent::__construct($message, $code, $previous);
        $this->entity = $entity instanceof EntityInterface ? $entity::class : null;
        $this->attribute = $attribute;
    }
}
