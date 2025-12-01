<?php

use Slim\App;

return function (App $app) {
    try {
        foreach (glob( dirname(__FILE__) . '/../routes/*.php') as $routeFile) {
            require $routeFile;
        }       
    } catch (Exception $e) {
        logger()->error($e->getMessage(), [__FILE__]);        
    }
};