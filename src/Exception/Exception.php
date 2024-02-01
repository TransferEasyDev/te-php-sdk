<?php

declare(strict_types=1);

namespace Transfereasy\Pay\Exception;

class Exception extends \Exception
{
    public const UNKNOWN = 9999;

    public const UNKNOWN_SERVICE = 9998;
    public const PARAMS_SHORTCUT_ACTION_INVALID = 9400;

    public const PARAMS_METHOD_NOT_SUPPORTED = 9401;

    public const DECRYPT_ERROR = 9413;



}


