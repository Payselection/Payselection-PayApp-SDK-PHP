<?php

namespace PaySelection\Request;

use PaySelection\BaseRequest;

class WebPayment extends BaseRequest
{
    public string  $amount;
    public string  $currency;
    public string  $orderId;
    public string  $description;
    public array   $extraData;
    public array   $customerInfo;
    private string $paymentType;

    /**
     * WebPayment constructor.
     * @param string $paymentType
     */
    public function __construct(string $paymentType)
    {
        $this->paymentType = $paymentType;
    }

    public function makeRequest(): array
    {
        $cf = [
            'Language' => 'RU',
        ];
        $customerInfo = array_merge($cf, $this->customerInfo);
        return [
            'MetaData' => [
                'PaymentType' => $this->paymentType,
            ],
            'PaymentRequest' => [
                'Amount' => $this->roundNumber($this->amount),
                'Currency' => $this->currency,
                'Description' => $this->description,
                'RebillFlag' => false,
                'OrderId' => $this->orderId,
                'ExtraData' => $this->extraData,
            ],
            'CustomerInfo' => $customerInfo,
        ];
    }
}
