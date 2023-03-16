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
    public array   $receiptInfo;
    public array   $request;
    private string $paymentType;

    /**
     * WebPayment constructor.
     * @param string $paymentType
     */
    public function __construct(string $paymentType)
    {
        $this->paymentType = $paymentType;
    }

    /**
     * @return array
     */
    public function makeRequest(): array
    {
        $cf = [
            'Language' => 'RU',
        ];
        $customerInfo = array_merge($cf, $this->customerInfo);

        $req = [
            'MetaData' => [
                'PaymentType' => $this->paymentType,
            ],
            'PaymentRequest' => [
                'Amount' => $this->roundNumber($this->amount),
                'Currency' => $this->currency,
                'Description' => $this->description,
                'RebillFlag' => false,
                'OrderId' => $this->orderId,
            ],
            'CustomerInfo' => $customerInfo,
        ];

        if (!empty($this->receiptInfo) && is_array($this->receiptInfo)) {
            $req['ReceiptData'] = [
                'timestamp' => date('d.m.Y H:i:s'),
                'external_id' => $this->orderId,
                'receipt' => $this->receiptInfo
            ];
        }

        if ($this->extraData) {
            $req['PaymentRequest'] += ['ExtraData' => $this->extraData];
        }

        return $req;
    }
}
