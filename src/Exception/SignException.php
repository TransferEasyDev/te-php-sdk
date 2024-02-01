<?php
declare(strict_types=1);

namespace Transfereasy\Pay\Pay\Exception;
use Throwable;
class SignException extends Exception
{
    public function __construct(
        int $code = self::DECRYPT_ERROR,
        string $message = 'sign error',
        mixed $extra = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $extra, $previous);
    }
}