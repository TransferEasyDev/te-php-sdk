<?php

declare(strict_types=1);
namespace Transfereasy\Pay;

use ReflectionClass;
use ReflectionException;
use Transfereasy\Pay\Exception\CustomerException;
use Transfereasy\Pay\Exception\Exception;
use Transfereasy\Pay\Service\Payment\V1\TransactionService;
use Transfereasy\Pay\Service\Payment\V2\TransactionService as transactionServiceV2;
use Transfereasy\Pay\Util\ParamCheck;

/**
 * @method static TransactionService transaction(array $config = [])
 * @method static TransactionServiceV2 transactionV2(array $config = [])
 */
class TE {
    /**
     * @throws CustomerException
     * @throws ReflectionException
     */
    public static function __callStatic(string $service, array $config = [])
    {
        if (!empty($config)) {
            $config = self::config(...$config);
        }

        $get_service = explode('V', $service);

        $version = '1';
        if (count($get_service) > 1 && $get_service[1] == '2') {
            $version = $get_service[1];
        }

        $config['domain'] = "https://api.transfereasy.com";
        if (isset($config["env"]) && $config['env'] != 'prod') {
            $config['domain'] = "https://test-newapi.transfereasy.com";
        }

        $className = '\Transfereasy\Pay\Service\Payment\V'.$version.'\\' . ucfirst($get_service[0]) . 'Service';

        if (class_exists($className)) {
            $reflectionClass = new ReflectionClass($className);
            return $reflectionClass->newInstanceArgs([$config]);
        }
        throw new CustomerException(Exception::SERVICE_IS_NOT_FOUND);
    }

    /**
     * @throws CustomerException
     */
    public static function config(array $config = []):array
    {
        //检测config
        ParamCheck::check($config);

        return $config;
    }
}