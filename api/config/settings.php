<?php

// Should be set to 0 in production
error_reporting(error_level: 0); //E_ALL

// Should be set to '0' in production
ini_set('display_errors', 'Off');
// ini_set('log_errors', 'On');
// ini_set('error_log', __DIR__. '/log/php_errors.log');

// Settings
$settings = [];

// ...

date_default_timezone_set('America/Sao_Paulo');

return $settings;