<?php

namespace PaySelection;

use PaySelection\Exceptions\BadTypeException;
use PaySelection\Model\ReceiptData;
use PaySelection\Model\RecurrentDetails;
use PaySelection\Model\Transaction\TransactionStateDetails;

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

        $request = $this->formatString($request);

        $request_enc = json_decode($request, true);
        if ($request_enc === null && json_last_error() !== JSON_ERROR_NONE) {
            $request = stripcslashes($request);
            $request_enc = json_decode($request, true);
            if ($request_enc === null && json_last_error() !== JSON_ERROR_NONE) {
                throw new BadTypeException('Can\'t decode JSON: ' . json_last_error_msg());
            }
        }

        $this->fill($request_enc);
    }

    /**
     * @return void
     * @throws BadTypeException
     */
    public function customHook()
    {
        $request = file_get_contents('php://input');
        $request = '{"OrderId": "1299670125", "Amount": "4.50", "Description": "Description", "RebillFlag": false}';

        if (empty($request)) throw new BadTypeException('Request not found');

        $request = $this->formatString($request);

        $request_enc = json_decode($request, true);
        if ($request_enc === null && json_last_error() !== JSON_ERROR_NONE) {
            $request = stripcslashes($request);
            $request_enc = json_decode($request, true);
            if ($request_enc === null && json_last_error() !== JSON_ERROR_NONE) {
                throw new BadTypeException('Can\'t decode JSON: ' . json_last_error_msg());
            }
        }
        $this->fill($request_enc);
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

            if (!isset($request[$requestKey])) {
                continue;
            }

            $value = $request[$requestKey];

            if (is_array($value)) {
                $this->handleArrayValue($key, $value);
                continue;
            }

            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function formatString(string $request): string
    {
        // Replace escaped sequences with proper UTF-8 characters
        $request = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($matches) {
            return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UCS-2BE');
        }, $request);

        return preg_replace_callback('/\\\\x([0-9a-fA-F]{2})/', function ($matches) {
            return chr(hexdec($matches[1]));
        }, $request);
    }

    protected function handleArrayValue($key, $value)
    {
        if ($key === 'recurrent') {
            $this->$key = $this->assignObjectFromArray(new RecurrentDetails(), $value);
        } elseif ($key === 'receiptData') {
            $this->$key = $this->assignObjectFromArray(new ReceiptData(), $value);
        }
    }

    protected function assignObjectFromArray($object, array $data)
    {
        $modelInnerFields = get_object_vars($object);

        foreach ($modelInnerFields as $keyInner => $fieldInner) {
            $responseInnerKey = ucfirst($keyInner);

            if (!isset($data[$responseInnerKey])) {
                continue;
            }

            $valueInner = $data[$responseInnerKey];

            if (!is_array($valueInner)) {
                $object->$keyInner = $valueInner;
                continue;
            }

            if ('TransactionStateDetails' === $responseInnerKey) {
                $object->$keyInner = $this->assignObjectFromArray(new TransactionStateDetails(), $valueInner);
            }
        }

        return $object;
    }
}

if (!function_exists('getallheaders')) {

    /**
     * Get all HTTP header key/values as an associative array for the current request.
     *
     * @return string[string] The HTTP header key/value pairs.
     */
    function getallheaders()
    {
        $headers = array();

        $copy_server = array(
            'CONTENT_TYPE'   => 'Content-Type',
            'CONTENT_LENGTH' => 'Content-Length',
            'CONTENT_MD5'    => 'Content-Md5',
        );

        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $key = substr($key, 5);
                if (!isset($copy_server[$key]) || !isset($_SERVER[$key])) {
                    $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
                    $headers[$key] = $value;
                }
            } elseif (isset($copy_server[$key])) {
                $headers[$copy_server[$key]] = $value;
            }
        }

        if (!isset($headers['Authorization'])) {
            if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            } elseif (isset($_SERVER['PHP_AUTH_USER'])) {
                $basic_pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
                $headers['Authorization'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $basic_pass);
            } elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
                $headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
            }
        }

        return $headers;
    }

}
