<?php
declare(strict_types=1);
namespace Transfereasy\Pay\Util;
use Transfereasy\Pay\Exception\Exception;
use Transfereasy\Pay\Exception\SignException;

class ESA
{
    /**
     * 解析私钥
     * @throws SignException
     */
    public static function getPrivateKey($privateKeyPath, $passphrase = null)
    {
        // 读取私钥文件内容
        $privateKey = file_get_contents($privateKeyPath);

        // 加载私钥
        $privateKeyResource = openssl_pkey_get_private($privateKey, $passphrase);

        if (!$privateKeyResource) {
            // 私钥加载失败，处理错误情况
            throw new SignException(Exception::SERVER_ERROR, 'key read error');
        }

        return $privateKeyResource;
    }


    /**
     * @throws SignException
     */
    public static function getPublicKey($publicKeyPath)
    {
        // 读取公钥文件内容
        $publicKey = file_get_contents($publicKeyPath);

        // 加载公钥
        $publicKeyResource = openssl_pkey_get_public($publicKey);

        if (!$publicKeyResource) {
            // 公钥加载失败，处理错误情况
            throw new SignException(Exception::SERVER_ERROR, 'key read error');
        }

        return $publicKeyResource;
    }
}