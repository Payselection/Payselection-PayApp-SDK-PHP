<?php

namespace PaySelection\Hook;

use PaySelection\BaseHook;
use PaySelection\Model\ReceiptData;
use PaySelection\Model\RecurrentDetails;

class HookRecurring extends BaseHook
{
    public ?string $event               = null;
    public ?string $rebillId            = null;
    public ?string $amount              = null;
    public ?string $currency            = null;
    public ?string $description         = null;
    public ?string $webhookUrl          = null;
    public ?string $accountId           = null;
    public ?string $email               = null;
    public ?string $startDate           = null;
    public ?string $interval            = null;
    public ?string $period              = null;
    public ?string $maxPeriod           = null;
    public ?string $recurringId         = null;
    public ?ReceiptData $receiptData    = null;
    public ?RecurrentDetails $recurrent = null;

}
