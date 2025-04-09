<?php

namespace App\Domain\Enums;

enum BillingTypeEnum: string
{
    case BOLETO = 'boleto';
    case PIX = 'pix';
    case CREDIT_CARD = 'credit_card';
}
