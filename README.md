# Payselection api Library

## Оглавление

- [Установка](#установка)
- [Начало работы](#начало-работы)

## Установка

Установить библиотеку можно с помощью composer:

```
composer require payselection/payselection-php-sdk
```

## Начало работы

```php
$apiClient = new \PaySelection\Library();
$response = $apiClient->webPayCreate(
    100,
    'RUB',
    'a3a393d8-ac47-11ed-afa1-0242ac120002',
    'item #144',
    array(
        'WebhookUrl' => 'https://webhook.site/38fb8867-a648-423e-9146-30576b2ad8e4'
    ),
    array(
        'ZIP' => '222410',
        'Language' => 'RU'
    ),
    array(
        'client' => [
            'name' => 'Inan Ivanov',
            'email' => 'ivan@example.com',
        ],
        'company' => [
            'email' => 'company@example.com',
            'inn' => '12345',
            'payment_address' => 'company address',
        ],
        'items' => [
            [
                'name' => 'Product title 1',
                'price' => 123.00,
                'quantity' => 1,
                'sum' => 123.00,
                'payment_method' => 'full_prepayment',
                'payment_object' => 'commodity',
                'vat' => [
                    'type' => 'vat0'
                ]
            ],
            [
                'name' => 'Product title 2',
                'price' => 10.00,
                'quantity' => 2,
                'sum' => 20.00,
                'payment_method' => 'full_prepayment',
                'payment_object' => 'commodity',
                'vat' => [
                    'type' => 'vat0'
                ]
                ],
        ],
        'payments' => [
            [
                'type' => 1, 
                'sum' => 143.00
            ]
        ],
        'total' => 143.00     
    )
);

echo $response->redirectUrl;
```

## License

MIT
