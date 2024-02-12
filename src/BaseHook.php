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
        $headers = array_change_key_case(getallheaders());

        if (empty($request)) throw new BadTypeException('Request not found');
        if (empty($headers['x-site-id'])) throw new BadTypeException('X-Site-Id not found');
        $x_siteId = $headers['x-site-id'];
        if ($siteId !== $x_siteId) throw new BadTypeException(sprintf('X-Site-Id[%s] not math with siteId[%s]', $x_siteId, $siteId));
        if (empty($headers['x-webhook-signature'])) throw new BadTypeException('Signature not found');

        // Check signature
        $signBody = 'POST' . PHP_EOL .
            $webhookUrl . PHP_EOL .
            $siteId . PHP_EOL .
            $request;

        if ($headers['x-webhook-signature'] !== self::getSignature($signBody, $secretKey))
            throw new BadTypeException('Signature error');

        $request = stripcslashes($request);
        $request = json_decode($request, true);
        if ($request === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new BadTypeException('Can\'t decode JSON: ' . json_last_error_msg());
        }

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

    public function fill(array $request)
    {
        $modelFields = get_object_vars($this);
        foreach ($modelFields as $key => $field) {
            $requestKey = ucfirst($key);
            if (isset($request[$requestKey])) { 
                $value = $request[$requestKey];
                if (!is_array($value)) {
                    if (property_exists($this, $key)) {
                        $this->$key = $request[$requestKey];
                    }
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
