<?php

namespace PaySelection\Response;

use PaySelection\BaseRequest;
use PaySelection\Response\Models\BaseModel;
use Psr\Http\Message\ResponseInterface;

class PSResponse extends BaseRequest
{
    public bool    $success;
    public ?string $message = null;
    public ?string $warning = null;

    public $model;

    public function fillByResponse(ResponseInterface $response): self
    {
        $responseContent = json_decode($response->getBody()->getContents());

        $this->success = $responseContent->Success ?? false;
        $this->message = $responseContent->Message ?? 'Message is not set';
        $this->warning = $responseContent->Warning ?? 'Warning is not set';
        if (!empty($responseContent->Model)) {
            $this->fillModel($responseContent->Model);
        }

        return $this;
    }

    public function fillModel($modelDate)
    {
        $model = $modelDate;
        if (is_object($modelDate)) {
            $model = new BaseModel();
            $model->fill($modelDate);
        }

        $this->model = $model;
    }
}
