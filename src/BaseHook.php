<?php

namespace PaySelection;

use PaySelection\Exceptions\BadTypeException;

/**
 *
 */
class BaseHook
{
    /**
     * @var array
     */
    protected array   $request;
    public string  $siteId;
    public string  $secretKey;
    public string  $webhookUrl;

    /**
     * @return void
     * @throws BadTypeException
     */
    public function hook()
    {
        $request = file_get_contents('php://input');
        $headers = getallheaders();
        if (
            empty($request) ||
            empty($headers['X-Site-Id']) ||
            $this->siteId != $headers['X-Site-Id'] ||
            empty($headers['X-Webhook-Signature'])
        )
            throw new BadTypeException('Not found');

        // Check signature
        $signBody = 'POST' . PHP_EOL .
            $this->webhookUrl . PHP_EOL .
            $this->siteId . PHP_EOL .
            $request;

        if ($headers['X-Webhook-Signature'] !== self::getSignature($signBody, $this->secretKey))
            throw new BadTypeException('Signature error');

        $request = json_decode($request, true);
        if (!$request)
            throw new BadTypeException('Can\'t decode JSON');

        $this->request = $request;
        $this->fill();
    }

    /**
     * @return array
     */
    public function getRequest(): array
    {
        return $this->request;
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

    /**
     *
     */
    private function fill()
    {
        $modelFields = get_object_vars($this);
        foreach ($modelFields as $key => $field) {
            $requestKey = ucfirst($key);
            if (isset($this->request[$requestKey])) {
                $this->$key = $this->request[$requestKey];
            }
        }
    }
}
