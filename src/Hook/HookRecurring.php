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

    private function fill($request)
    {
        $modelFields = get_object_vars($this);
        foreach ($modelFields as $key => $field) {
            $requestKey = ucfirst($key);
            if (isset($request[$requestKey])) { 
                $value = $request[$requestKey];
                if (!is_object($value)) {
                    $this->$key = $request[$requestKey];
                } else {
                    if ('Recurrent' === $requestKey) {
                        $this->$key = new RecurrentDetails();
                        $modelInnerFields = get_object_vars($this->$key);
                        foreach ($modelInnerFields as $keyInner => $fieldInner) {
                            $responseInnerKey = ucfirst($keyInner);
                            if (isset($value[$responseInnerKey])) {
                                $valueInner = $value[$responseInnerKey];
                                $this->{$key}->{$keyInner} = $valueInner;
                            }
                        }
                    }
                    if ('ReceiptData' === $requestKey) {
                        $this->$key = new RecurrentDetails();
                        $modelInnerFields = get_object_vars($this->$key);
                        foreach ($modelInnerFields as $keyInner => $fieldInner) {
                            $responseInnerKey = ucfirst($keyInner);
                            if (isset($value[$responseInnerKey])) {
                                $valueInner = $value[$responseInnerKey];
                                $this->{$key}->{$keyInner} = $valueInner;
                            }
                        }
                    }
                }
                
            }
        }
    }

}
