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
    )
);

echo $response->redirectUrl;
```

## License

MIT
