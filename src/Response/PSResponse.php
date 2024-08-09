<?php

namespace PaySelection\Response;

use PaySelection\BaseRequest;
use Psr\Http\Message\ResponseInterface;

class PSResponse extends BaseRequest
{
    public function fillByResponse(ResponseInterface $response): self
    {
        $responseContent = json_decode($response->getBody(), true);
        $this->fill($responseContent);
        return $this;
    }

    public function get_helper_object()
    {
        return false;
    }

    public function fill(array $responseContent)
    {
        $modelFields = get_object_vars($this);

        foreach ($modelFields as $key => $field) {
            $responseKey = ucfirst($key);
            if (isset($responseContent[$responseKey])) {
                $value = $responseContent[$responseKey];
                if (!is_array($value)) {
                    $this->{$key} = $value;
                } elseif ($helper_object = $this->get_helper_object()) {
                    $this->{$key} = $helper_object;
                    $modelInnerFields = get_object_vars($this->$key);
                    foreach ($modelInnerFields as $keyInner => $fieldInner) {
                        $responseInnerKey = ucfirst($keyInner);
                        if ($keyInner === 'rrn') $responseInnerKey = 'RRN';
                        if (isset($value[$responseInnerKey])) {
                            $valueInner = $value[$responseInnerKey];
                            $this->{$key}->{$keyInner} = $valueInner;
                        }
                    }
                }
            }
        }
        return $this;
    }
}
