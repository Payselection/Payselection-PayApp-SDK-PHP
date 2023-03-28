<?php

namespace PaySelection\Model\Transaction;

class StateDetails
{
    public ?string $amount             = null;
    public ?string $currency           = null;
    public ?string $processingAmount   = null;
    public ?string $payoutToken        = null;
    public ?string $processingCurrency = null;
    public ?string $remainingAmount    = null;
    public ?string $rebillId           = null;
    public ?string $code               = null;
    public ?string $description        = null;
    public ?string $acsUrl             = null;
    public ?string $paReq              = null;
    public ?string $mD                 = null;
    public ?string $redirectUrl        = null;
    public ?string $redirectMethod     = null;
}
