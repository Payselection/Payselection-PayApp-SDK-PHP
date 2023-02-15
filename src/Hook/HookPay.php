<?php

namespace PaySelection\Hook;

use PaySelection\BaseHook;

/**
 *
 */
class HookPay extends BaseHook
{
    // Required

    public ?string $transactionId  = null;
    public ?string $event          = null;
    public ?string $orderId        = null;
    public ?string $dateTime       = null;
    public ?string $isTest         = null;
    public ?string $cardMasked     = null;
    public ?string $customFields   = null;
    public ?string $description    = null;
    public ?string $amount         = null;
    public ?string $currency       = null;
    public ?string $expirationDate = null;
    public ?string $cardHolder     = null;


    /**
     *
     */
    public function handle()
    {

    }
}
