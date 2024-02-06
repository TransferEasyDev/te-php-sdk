<?php

declare(strict_types=1);

namespace Transfereasy\Pay\Exception;

class Exception extends \Exception
{
    public const UNKNOWN = 9999;

    public const UNKNOWN_SERVICE = 9998;
    public const PARAMS_SHORTCUT_ACTION_INVALID = 9400;

    public const PARAMS_METHOD_NOT_SUPPORTED = 9401;

    public const PARAMS_IS_NOT_ENOUGH = 9403;

    public const SERVICE_IS_NOT_FOUND = 9404;
    public const DECRYPT_ERROR = 9413;

    public const SERVER_ERROR = 10001;

    public const SERVER_REQUEST_ERROR = 10002;

    public const SERVER_REQUEST_RESULT_ERROR = 10002;



}


