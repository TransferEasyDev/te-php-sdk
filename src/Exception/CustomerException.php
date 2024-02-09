<?php
declare(strict_types=1);

namespace Transfereasy\Pay\Exception;
use Throwable;

class CustomerException extends Exception
{
    public function __construct(
        int $code = self::UNKNOWN_SERVICE,
        string $message = 'customer error',
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
