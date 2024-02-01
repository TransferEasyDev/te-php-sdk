<?php
declare(strict_types=1);

namespace Transfereasy\Pay\Exception;
use Throwable;

class DecryptException extends Exception
{
    public function __construct(
        int $code = self::DECRYPT_ERROR,
        string $message = 'decrypt error',
        mixed $extra = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $extra, $previous);
    }
}