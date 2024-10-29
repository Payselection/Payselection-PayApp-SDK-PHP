<?php

namespace PaySelection\Hook;

use PaySelection\BaseHook;
use PaySelection\Model\ReceiptData;
use PaySelection\Model\RecurrentDetails;

class VerifyPaymentHook extends BaseHook
{
    public ?string $paymentType        = null;
    public ?string $typeLink           = null;
    public ?bool $previewForm          = null;
    public ?bool $sendSMS              = null;
    public ?string $orderId            = null;
    public ?string $amount             = null;
    public ?string $currency           = null;

    public ?string $email              = null;
    public ?string $phone              = null;
    public ?string $description        = null;
    public ?string $customFields       = null;
    public ?bool   $rebillFlag         = null;

    public ?string $receiptEmail       = null;
    public ?string $language           = null;
    public ?string $address            = null;
    public ?string $town               = null;
    public ?string $zIP                = null;
    public ?string $country            = null;
    public ?string $userId             = null;
    public ?string $interval           = null;
    public ?string $period             = null;
    public ?string $maxPeriods         = null;
    public ?string $startDate          = null;
    public ?string $accountId          = null;
    public ?ReceiptData $receiptData   = null;
}
