<?php

namespace PaySelection;

use PaySelection\Exceptions\BadTypeException;

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

    private function fill($request)
    {
        $modelFields = get_object_vars($this);
        foreach ($modelFields as $key => $field) {
            $requestKey = ucfirst($key);
            if (isset($request[$requestKey])) {
                $this->$key = $request[$requestKey];
            }
        }
    }
}
