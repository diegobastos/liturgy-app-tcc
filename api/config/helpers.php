<?php

if (!function_exists('logger')) {
    function logger(): \Psr\Log\LoggerInterface {
        global $container;
        return $container->get('logger');
    }
}

function getArrayValue(array $array, string $key) {
    if (!array_key_exists($key, $array)) {
        throw new Exception("Chave '{$key}' não encontrada no array.");
    }
    return $array[$key];
}