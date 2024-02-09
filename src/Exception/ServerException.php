<?php

namespace Transfereasy\Pay\Exception;
use Throwable;

class ServerException extends Exception
{
    public function __construct(
        int $code = self::SERVER_ERROR,
        string $message = 'decrypt error',
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}