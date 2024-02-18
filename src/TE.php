<?php


declare(strict_types=1);
namespace Transfereasy\Pay;

use ReflectionClass;
use ReflectionException;
use Transfereasy\Pay\Exception\CustomerException;
use Transfereasy\Pay\Exception\Exception;
use Transfereasy\Pay\Service\Payment\V1\TransactionService;
use Transfereasy\Pay\Util\ParamCheck;

/**
 * @method static TransactionService transaction(array $config = [])
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
        $version = '1';
        if (isset($config['version'])) {
            $version = $config['version'];
        }

        $config['domain'] = "https://api.transfereasy.com";
        if (isset($config["env"]) && $config['env'] != 'prod') {
            $config['domain'] = "https://test-newapi.transfereasy.com";
        }
        
        $className = '\Transfereasy\Pay\Service\Payment\V'.$version.'\\' . ucfirst($service) . 'Service';
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
