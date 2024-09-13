<?php

namespace PaySelection;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use PaySelection\Enum\PaymentType;
use PaySelection\Enum\PSMethodsEnum;
use PaySelection\Exceptions\BadTypeException;
use PaySelection\Exceptions\PSResponseException;
use PaySelection\Hook\HookPay;
use PaySelection\Request\ExtendedRequest;
use PaySelection\Request\StatusRequest;
use PaySelection\Request\WebPayment;
use PaySelection\Response\PaylinkResponse;
use PaySelection\Response\PaylinkVoidResponse;
use PaySelection\Response\BalanceResponse;
use PaySelection\Response\CancelResponse;
use PaySelection\Response\ChargeResponse;
use PaySelection\Response\ConfirmResponse;
use PaySelection\Response\OrderResponse;
use PaySelection\Response\OrderExtResponse;
use PaySelection\Response\PayResponse;
use PaySelection\Response\PSResponse;
use PaySelection\Response\RecurringCancelResponse;
use PaySelection\Response\RecurringChangeResponse;
use PaySelection\Response\RecurringSearchResponse;
use PaySelection\Response\RecurringResponse;
use PaySelection\Response\RefundResponse;
use PaySelection\Response\TransactionExtResponse;
use PaySelection\Response\TransactionResponse;
use PaySelection\Response\UnsubscribeResponse;
use PaySelection\Response\WebPayResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Library
{
    protected string  $siteId;
    protected string  $secretKey;
    protected string  $webpayUrl;
    protected string  $apiUrl;
    protected Client  $client;
    private   array   $configParams;

    /**
     * @param string|null $filePath
     */
    public function __construct(string $filePath = null)
    {
        $this->loadConfiguration($filePath);
    }

    /**
     * @param array $config
     * @return $this
     */
    public function setConfiguration(array $config): Library {
        $paramsArray = array_merge($this->configParams, $config);
        $this->configParams = $paramsArray;

        return $this;
    }

    /**
     * https://api.payselection.com/#tag/webpay
     * @param $amount
     * @param string $currency
     * @param string $orderId
     * @param string $description
     * @param array|null $extraData
     * @param array|null $customerInfo
     * @param array|null $receiptInfo
     * @return WebPayResponse
     * @throws GuzzleException
     */
    public function webPayCreate(
        $amount,
        string $currency,
        string $orderId,
        string $description,
        ?array $extraData = array(),
        ?array $customerInfo = array(),
        ?array $receiptInfo = array()
    ): WebPayResponse
    {
        $method = PSMethodsEnum::PAYMENTS_WEBPAY;
        $this->createClient($method);

        $webPaymentData = new WebPayment(PaymentType::PAY);
        $webPaymentData->amount       = $amount;
        $webPaymentData->currency     = $currency;
        $webPaymentData->orderId      = $orderId;
        $webPaymentData->description  = $description;
        $webPaymentData->extraData    = $extraData;
        $webPaymentData->customerInfo = $customerInfo;
        $webPaymentData->receiptInfo  = $receiptInfo;

        return $this->request($method, $webPaymentData->makeRequest(), 'POST', new WebPayResponse());
    }

    /**
     * https://api.payselection.com/#operation/Create
     * Создайте платёж, чтобы Покупатель смог оплатить его
     * @param array $request
     * @return WebPayResponse
     * @throws GuzzleException
     */
    public function createWebPay(array $request): WebPayResponse
    {
        $method = PSMethodsEnum::PAYMENTS_WEBPAY;
        $this->createClient($method);

        $data = new ExtendedRequest($request);

        return $this->request($method, $data->makeRequest(), 'POST', new WebPayResponse());
    }

    /**
     * https://api.payselection.com/#operation/Paylink%20Create
     * Метод позволяет создать ссылку для перехода на платежный виджет.
     * @param array $request
     * @return PaylinkResponse
     * @throws GuzzleException
     */
    public function createPaylink(array $request): PaylinkResponse
    {
        $method = PSMethodsEnum::PAYMENTS_PAYLINK_CREATE;
        $this->createClient($method);

        $data = new ExtendedRequest($request);

        return $this->request($method, $data->makeRequest(), 'POST', new PaylinkResponse());
    }

    /**
     * https://api.payselection.com/#operation/Paylink%20Void
     * Метод позволяет отменить ссылку на платежный виджет.
     * @param array $request
     * @return PaylinkVoidResponse
     * @throws GuzzleException
     */
    public function createPaylinkVoid(array $request): PaylinkVoidResponse
    {
        $method = PSMethodsEnum::PAYMENTS_PAYLINK_VOID;
        $this->createClient($method);

        $data = new ExtendedRequest($request);

        return $this->request($method, $data->makeRequest(), 'POST', new PaylinkVoidResponse());
    }

    /**
     * https://api.payselection.com/#operation/Pay
     * Одностадийная операция оплаты – денежные средства списываются сразу после ее проведения.
     * @param array $request
     * @return PayResponse
     * @throws GuzzleException
     */
    public function createPayment(array $request): PayResponse
    {
        $method = PSMethodsEnum::PAYMENTS_PAY;
        $this->createClient($method);

        $data = new ExtendedRequest($request);

        return $this->request($method, $data->makeRequest(), 'POST', new PayResponse());
    }

    /**
     * https://api.payselection.com/#operation/Block
     * Двухстадийная операция оплаты – денежные средства блокируются на карте.
     * @param array $request
     * @return PayResponse
     * @throws GuzzleException
     */
    public function CreateBlock(array $request): PayResponse
    {
        $method = PSMethodsEnum::PAYMENTS_BLOCK;
        $this->createClient($method);

        $data = new ExtendedRequest($request);

        return $this->request($method, $data->makeRequest(), 'POST', new PayResponse());
    }

    /**
     * https://api.payselection.com/#operation/Refund
     * Только успешная транзакция может быть возвращена
     * @param array $request
     * @return RefundResponse
     * @throws GuzzleException
     */
    public function createRefund(array $request): RefundResponse
    {
        $method = PSMethodsEnum::PAYMENTS_REFUND;
        $this->createClient($method);

        $data = new ExtendedRequest($request);

        return $this->request($method, $data->makeRequest(), 'POST', new RefundResponse());
    }

    /**
     * https://api.payselection.com/#operation/Сonfirm
     * Используется для операций Pay или Block с 3DS после получения результатов аутентификации от банка
     * для завершения одностадийной/двухстадийной операции оплаты.
     * @param array $request
     * @return ConfirmResponse
     * @throws GuzzleException
     */
    public function confirmPayment(array $request): ConfirmResponse
    {
        $method = PSMethodsEnum::PAYMENTS_CONFIRM;
        $this->createClient($method);

        $data = new ExtendedRequest($request);

        return $this->request($method, $data->makeRequest(), 'POST', new ConfirmResponse());
    }

    /**
     * https://api.payselection.com/#operation/Charge
     * Списание средств с карты в рамках проведенной ранее двухстадийной операции оплаты.
     * @param array $request
     * @return ChargeResponse
     * @throws GuzzleException
     */
    public function chargePayment(array $request): ChargeResponse
    {
        $method = PSMethodsEnum::PAYMENTS_CHARGE;
        $this->createClient($method);

        $data = new ExtendedRequest($request);

        return $this->request($method, $data->makeRequest(), 'POST', new ChargeResponse());
    }

    /**
     * https://api.payselection.com/#operation/Cancel
     * Отмена блокировки средств на карте в рамках ранее проведенной двухстадийной операции оплаты.
     * @param array $request
     * @return CancelResponse
     * @throws GuzzleException
     */
    public function cancelPayment(array $request): CancelResponse
    {
        $method = PSMethodsEnum::PAYMENTS_CANCEL;
        $this->createClient($method);

        $data = new ExtendedRequest($request);

        return $this->request($method, $data->makeRequest(), 'POST', new CancelResponse());
    }

    /**
     * https://api.payselection.com/#operation/Rebill
     * Операция автоматического списания средств по привязанной ранее карте.
     * @param array $request
     * @return PayResponse
     * @throws GuzzleException
     */
    public function rebillPayment(array $request): PayResponse
    {
        $method = PSMethodsEnum::PAYMENTS_REBILL;
        $this->createClient($method);

        $data = new ExtendedRequest($request);

        return $this->request($method, $data->makeRequest(), 'POST', new PayResponse());
    }

    /**
     * https://api.payselection.com/#operation/Unsubscribe
     * Отмена рекуррентных платежей.
     * При использовании данного метода произойдет отписка
     * по всем зарегистрированным регулярным оплатам в рамках переданного RebillId
     * @param array $request
     * @return UnsubscribeResponse
     * @throws GuzzleException
     */
    public function cancelSubscription(array $request): UnsubscribeResponse
    {
        $method = PSMethodsEnum::PAYMENTS_UNSUBSCRIBE;
        $this->createClient($method);

        $data = new ExtendedRequest($request);

        return $this->request($method, $data->makeRequest(), 'POST', new UnsubscribeResponse());
    }

    /**
     * https://api.payselection.com/#operation/Recurring
     * Регистрация регулярной оплаты по привязанной ранее карте.
     * @param array $request
     * @return RecurringResponse
     * @throws GuzzleException
     */
    public function registerRecurring(array $request): RecurringResponse
    {
        $method = PSMethodsEnum::PAYMENTS_RECURRING;
        $this->createClient($method);

        $data = new ExtendedRequest($request);

        return $this->request($method, $data->makeRequest(), 'POST', new RecurringResponse());
    }

    /**
     * https://api.payselection.com/#operation/Recurring%20Unsubscribe
     * Отмена регулярной оплаты.
     * @param array $request
     * @return RecurringCancelResponse
     * @throws GuzzleException
     */
    public function cancelRecurring(array $request): RecurringCancelResponse
    {
        $method = PSMethodsEnum::PAYMENTS_RECURRING_UNSUBSCRIBE;
        $this->createClient($method);

        $data = new ExtendedRequest($request);

        return $this->request($method, $data->makeRequest(), 'POST', new RecurringCancelResponse());
    }

    /**
     * https://api.payselection.com/#operation/Recurring%20Change
     * Изменение параметров регулярной оплаты.
     * @param array $request
     * @return RecurringChangeResponse
     * @throws GuzzleException
     */
    public function changeRecurring(array $request): RecurringChangeResponse
    {
        $method = PSMethodsEnum::PAYMENTS_RECURRING_CHANGE;
        $this->createClient($method);

        $data = new ExtendedRequest($request);

        return $this->request($method, $data->makeRequest(), 'POST', new RecurringChangeResponse());
    }

    /**
     * https://api.payselection.com/#operation/Recurring%20Search
     * Поиск регулярной оплаты (подписки) по выбранному параметру.
     * @param array $request
     * @return RecurringSearchResponse
     * @throws GuzzleException
     */
    public function searchRecurring(array $request): RecurringSearchResponse
    {
        $method = PSMethodsEnum::PAYMENTS_RECURRING_SEARCH;
        $this->createClient($method);

        $data = new ExtendedRequest($request);

        return $this->request($method, $data->makeRequest(), 'POST', new RecurringSearchResponse());
    }

    /**
     * https://api.payselection.com/#operation/Payout
     * Credit transaction - это тип транзакции, когда денежные средства переводятся на счет держателя карты.
     * Денежные средства зачисляются на карту в течение двух банковских дней.
     * @param array $request
     * @return PayResponse
     * @throws GuzzleException
     */
    public function createPayout(array $request): PayResponse
    {
        $method = PSMethodsEnum::PAYMENTS_PAYOUT;
        $this->createClient($method);

        $data = new ExtendedRequest($request);

        return $this->request($method, $data->makeRequest(), 'POST', new PayResponse());
    }

    /**
     * https://api.payselection.com/#operation/TransactionId:
     * Получить статус по TransactionId.
     * @param string $id
     * @return TransactionResponse
     * @throws GuzzleException
     */
    public function getTransactionStatus(string $id): TransactionResponse
    {
        $method = PSMethodsEnum::TRANSACTION_STATUS;
        $this->createClient($method);

        $data = new StatusRequest($id);

        return $this->request(sprintf($method, $data->id), $data->makeRequest(), 'GET', new TransactionResponse());
    }

    /**
     * https://api.payselection.com/#operation/TransactionId%20(extended):
     * Расширенный запрос используется для получения информации о текущем статусе по идентификатору транзакции.
     * @param string $id
     * @return TransactionExtResponse
     * @throws GuzzleException
     */
    public function getTransactionStatusExt(string $id): TransactionExtResponse
    {
        $method = PSMethodsEnum::TRANSACTION_STATUS_EXT;
        $this->createClient($method);

        $data = new StatusRequest($id);

        return $this->request(sprintf($method, $data->id), $data->makeRequest(), 'GET', new TransactionExtResponse());
    }

    /**
     * https://api.payselection.com/#operation/Transactions%20(by-dates):
     * Расширенный запрос используется для получения статуса транзакций по выбранному диапазону дат.
     * @param array $request
     * @return OrderExtResponse
     * @throws GuzzleException
     */
    public function getTransactionStatusByDates(array $request): OrderExtResponse
    {
        $method = PSMethodsEnum::TRANSACTIONS_BY_DATE_STATUS;
        $this->createClient($method);

        $data = new ExtendedRequest($request);

        return $this->request($method, $data->makeRequest(), 'POST', new OrderExtResponse());
    }

    /**
     * https://api.payselection.com/#operation/OrderId:
     * Получить статус по OrderId.
     * @param string $id
     * @return OrderResponse
     * @throws GuzzleException
     */
    public function getOrderStatus(string $id): OrderResponse
    {
        $method = PSMethodsEnum::ORDER_STATUS;
        $this->createClient($method);

        $data = new StatusRequest($id);

        return $this->request(sprintf($method, $data->id), $data->makeRequest(), 'GET', new OrderResponse());
    }

    /**
     * https://api.payselection.com/#operation/OrderId%20(extended):
     * Расширенный запрос используется для получения информации о текущем статусе по идентификатору заказа OrderId.
     * @param string $id
     * @return OrderExtResponse
     * @throws GuzzleException
     */
    public function getOrderStatusExt(string $id): OrderExtResponse
    {
        $method = PSMethodsEnum::ORDER_STATUS_EXT;
        $this->createClient($method);

        $data = new StatusRequest($id);

        return $this->request(sprintf($method, $data->id), $data->makeRequest(), 'GET', new OrderExtResponse());
    }

    /**
     * https://api.payselection.com/#operation/Balance
     * Операция проверки доступного баланса для Payout.
     * @return BalanceResponse
     * @throws GuzzleException
     */
    public function getBalance(): BalanceResponse
    {
        $method = PSMethodsEnum::BALANCE;
        $this->createClient($method);

        return $this->request($method, [], 'GET', new BalanceResponse());
    }

    /**
     * @throws BadTypeException
     */
    public function hookPay(): HookPay
    {
        $hook = new HookPay();
        $siteId     = $this->configParams['site_id'];
        $secretKey  = $this->configParams['secret_key'];
        $webhookUrl = $this->configParams['webhook_url'];
        $hook->hook($siteId, $secretKey, $webhookUrl);
        return $hook;
    }

    /**
     * @param string $method
     * @param array $postData
     * @param string $requestMethod
     * @param PSResponse|null $psResponse
     * @return PSResponse
     * @throws GuzzleException
     */
    protected function request(string $method, array $postData = [], string $requestMethod = 'POST', ?PSResponse $psResponse = null): PSResponse
    {
        $response = $this->sendRequest($method, $requestMethod, $postData);

        $psResponse = $psResponse ?? new PSResponse();
        return $psResponse->fillByResponse($response);
    }

    /**
     * @param string $method
     * @param string $requestMethod
     * @param array $postData
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function sendRequest(string $method, string $requestMethod, array $postData = []): ResponseInterface
    {
        $uid = $this->getIdempotenceKey();
        $msg = $requestMethod . PHP_EOL .
            '/' . $method . PHP_EOL .
            $this->siteId . PHP_EOL .
            $uid . PHP_EOL .
            json_encode($postData);

        $headers = [
            'X-Request-ID' => $uid,
            'X-Request-Signature' => $this->getSignature($msg, $this->secretKey)
        ];

        $options = ['json' => $postData];
        $options['headers'] = $headers;

        if ( 'GET' === $requestMethod ) {
            return $this->client->get($method, $options, ['http_errors' => false]);
        } else {
            return $this->client->post($method, $options, ['http_errors' => false]);
        }
    }

    /**
     * @return string
     */
    private function getIdempotenceKey(): string
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * @param string $body
     * @param string $secretKey
     * @return string
     */
    private function getSignature(string $body, string $secretKey): string
    {
        return hash_hmac('sha256', $body, $secretKey);
    }

    /**
     * @param $filePath
     * @return void
     */
    private function loadConfiguration($filePath = null): void
    {
        if ($filePath) {
            $data = file_get_contents($filePath);
        } else {
            $data = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "configuration.json");
        }

        $paramsArray = json_decode($data, true);
        $this->configParams = $paramsArray;
    }

    /**
     * @param string $method
     * @return void
     */
    private function createClient(string $method): void
    {
        $this->webpayUrl = $this->configParams['webpay_url'];
        $this->apiUrl    = $this->configParams['api_url'];
        $this->siteId     = $this->configParams['site_id'];
        $this->secretKey  = $this->configParams['secret_key'];

        if ($method === PSMethodsEnum::PAYMENTS_WEBPAY
            || $method === PSMethodsEnum::PAYMENTS_PAYLINK_CREATE
            || $method === PSMethodsEnum::PAYMENTS_PAYLINK_VOID) {
            $url = $this->webpayUrl;
        } else {
            $url = $this->apiUrl;
        }

        $stack = HandlerStack::create();
        $stack->push($this->httpErrorHandler());

        $this->client = new Client([
            'headers'  => ['X-Site-ID' => $this->siteId],
            'base_uri' => $url,
            'expect'   => false,
            'handler' => $stack
        ]);
    }

    function httpErrorHandler()
    {
        return function (callable $handler) {
            return function (
                $request,
                array $options
            ) use ($handler) {
                return $handler($request, $options)->then(
                    function (ResponseInterface $response) use ($request) {
                        if (!$response) {
                            $message = 'Error completing request';
                            throw new RequestException($message, $request, null, null, []);
                        }

                        $body = [];
                        $message = '';

                        $code = $response->getStatusCode();
                        if ($code < 400) {
                            return $response;
                        }

                        $level = (int) \floor($code / 100);
                        if ($level === 4) {
                            $message = 'Client error.';
                        } elseif ($level === 5) {
                            $message = 'Server error.';
                        } else {
                            $message = 'Unsuccessful request.';
                        }

                        $body = json_decode((string) $response->getBody(), true);

                        if (!json_last_error()) {
                            if ($body['Code']) {
                                $message .= sprintf(' Code error: %s.', $body['Code']);
                            }
    
                            if ($body['Description']) {
                                $message .= sprintf(' Description error: %s.', $body['Description']);
                            }
                        }

                        throw new PSResponseException($message, $request, $response, null, []);
                    }
                );
            };
        };
    }

}
