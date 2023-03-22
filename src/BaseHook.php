<?php

namespace PaySelection;

use PaySelection\Exceptions\BadTypeException;
use PaySelection\Model\ReceiptData;
use PaySelection\Model\RecurrentDetails;

/**
 *
 */
class BaseHook
{
    /**
     * @return void
     * @throws BadTypeException
     */
    public function hook(string $siteId, string $secretKey, string $webhookUrl)
    {
        $request = file_get_contents('php://input');
        $headers = getallheaders();
        if (
            empty($request) ||
            empty($headers['X-Site-Id']) ||
            $siteId != $headers['X-Site-Id'] ||
            empty($headers['X-Webhook-Signature'])
        )
            throw new BadTypeException('Not found');

        // Check signature
        $signBody = 'POST' . PHP_EOL .
            $webhookUrl . PHP_EOL .
            $siteId . PHP_EOL .
            $request;

        if ($headers['X-Webhook-Signature'] !== self::getSignature($signBody, $secretKey))
            throw new BadTypeException('Signature error');

        $request = json_decode($request, true);
        if (!$request)
            throw new BadTypeException('Can\'t decode JSON');

        $this->fill($request);
    }

    /**
     * @param string $body
     * @param string $secretKey
     * @return string
     */
    protected static function getSignature(string $body, string $secretKey): string
    {
        if (empty($body)) {
            return ";";
        }

        return hash_hmac("sha256", $body, $secretKey);
    }

    public function fill($request)
    {
        $modelFields = get_object_vars($this);
        foreach ($modelFields as $key => $field) {
            $requestKey = ucfirst($key);
            if (isset($request[$requestKey])) { 
                $value = $request[$requestKey];
                if (!is_array($value)) {
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
                        $this->$key = new ReceiptData();
                        $modelInnerFields = get_object_vars($this->$key);
                        foreach ($modelInnerFields as $keyInner => $fieldInner) {
                            $responseInnerKey = $keyInner;
                            if (isset($value[$keyInner])) {
                                $valueInner = $value[$keyInner];
                                $this->{$key}->{$keyInner} = $valueInner;
                            }
                        }
                    }
                }
                
            }
        }
    }
}
