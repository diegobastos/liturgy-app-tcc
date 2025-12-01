<?php

namespace Src\Base\Exception;

use Exception;

class DataServiceException extends Exception {}

class NotFoundException extends DataServiceException {
    protected $message = 'Data not found';
    protected $code = 404;
}

class UnauthorizedException extends DataServiceException {
    protected $message = 'Unauthorized';
    protected $code = 401;
}

class ServiceException extends DataServiceException {
    protected $message = 'Service error';
    protected $code = 500;
}