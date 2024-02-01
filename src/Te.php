<?php

declare(strict_types=1);
namespace Transfereasy\Pay;
use Transfereasy\Pay\Exception\CustomerException;
use Transfereasy\Pay\Exception\Exception;
use Transfereasy\Pay\Provider\Payment\TransactionProvider;
use Closure;

/**
 * @method static Transaction transaction(array $config = [], $container = null)
 * @method static Stock stock(array $config = [], $container = null)
 * @method static Customer customer(array $config = [], $container = null)
 */
class Te {


    public static function __callStatic(string $service, array $config = [])
    {
        if (!empty($config)) {
            self::config(...$config);
        }
    }

    public static function config(array $config = [], $container = null): bool
    {

    }

    public static function set(string $name, mixed $value): void
    {

    }

    public static function get(string $service): mixed
    {

    }

}