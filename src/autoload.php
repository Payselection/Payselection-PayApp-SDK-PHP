<?php

define('PS_SDK_ROOT_PATH', dirname(__FILE__));
define('PS_SDK_PSR_LOG_PATH', dirname(__FILE__).'/../vendor/psr/log/Psr/Log');

function psSdkLoadClass($className)
{
    if (strncmp('PaySelection', $className, 12) === 0) {
        $path   = PS_SDK_ROOT_PATH;
        $length = 12;
    } elseif (strncmp('Psr\Log', $className, 7) === 0) {
        $path   = PS_SDK_PSR_LOG_PATH;
        $length = 7;
    } else {
        return;
    }
    $path .= str_replace('\\', '/', substr($className, $length)) . '.php';

    if (file_exists($path)) {
        require $path;
    }
}

spl_autoload_register('psSdkLoadClass');
