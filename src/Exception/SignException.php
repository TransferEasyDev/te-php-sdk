<?php
declare(strict_types=1);

namespace Transfereasy\Pay\Exception;
use Throwable;
class SignException extends Exception
{
    public function __construct(
        int $code = self::DECRYPT_ERROR,
        string $message = 'sign error',
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}