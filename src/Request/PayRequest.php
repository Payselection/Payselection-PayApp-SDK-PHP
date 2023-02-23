<?php

namespace PaySelection\Request;

use PaySelection\BaseRequest;

class PayRequest extends BaseRequest
{
    public string  $orderId;
    public string  $amount;
    public string  $currency;
    public string  $description;
    public array   $extraData;
    public array   $customerInfo;
    public array   $request;
    private string $paymentMethod;

    /**
     * PayRequest constructor.
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

        if ($this->extraData) {
            $req['PaymentRequest'] += ['ExtraData' => $this->extraData];
        }

        return $req;
    }

    /**
     * @return array
     */
    public function makeRequestExtended(): array
    {
        $md = [
            'MetaData' => [
                'PaymentType' => $this->paymentType,
            ]
        ];

        return array_merge($md, $this->request);
    }
}
