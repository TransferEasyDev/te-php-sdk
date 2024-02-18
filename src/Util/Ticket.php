<?php

namespace Transfereasy\Pay\Util;

use Transfereasy\Pay\Exception\CustomerException;
use Transfereasy\Pay\Exception\Exception;
use Transfereasy\Pay\Exception\SignException;

class Ticket
{

    /**
     * 加签
     * @param $data
     * @param $private_key_path
     * @param $timestamp
     * @return string
     * @throws CustomerException
     * @throws SignException
     */
    public static function generateSignature($data, $private_key_path, $timestamp): string
    {
        // 构建URL键值对格式的参数字符串
        foreach ($data as $key => $value) {
            // 对键值进行URL编码
            if (is_array($value)) {
                $value = array_map(function($v_data) {
                    ksort($v_data, SORT_STRING);
                    return $v_data;
                }, $value);
                $data[$key] = json_encode($value, JSON_UNESCAPED_UNICODE);
            }
        }
        ksort($data, SORT_STRING);
        // 在参数字符串后面拼接Timestamp
        $signData = http_build_query($data).','.$timestamp;

        if (!file_exists($private_key_path)) {
            throw new CustomerException(Exception::FILE_NOT_SUPPORTED);
        }

        // 加载私钥
        $privateKey = openssl_pkey_get_private(file_get_contents($private_key_path));
        if (!$privateKey) {
            throw new SignException(Exception::DECRYPT_ERROR, openssl_error_string());
        }

        openssl_sign($signData, $sign, $privateKey, OPENSSL_ALGO_SHA256);

        // 将加密结果进行Base64编码
        $signature = base64_encode($sign);

        // 释放私钥资源
        openssl_free_key($privateKey);

        return $signature;
    }

    /**
     * 回调加密
     * @param $data
     * @param $timestamp
     * @param $public_key_path
     * @return string
     * @throws CustomerException
     */
    private static function generateSignatureByPublic($data, $timestamp, $public_key_path):string
    {
        // 将参数按ASCII码从小到大排序
        ksort($data);

        // 构建URL键值对格式的参数字符串
        $paramString = '';
        foreach ($data as $key => $value) {
            // 对键值进行URL编码
            $encodedValue = urlencode($value);
            $paramString .= $key . '=' . $encodedValue . '&';
        }
        $paramString = rtrim($paramString, '&');

        // 在参数字符串后面拼接Timestamp
        $paramString .= ',' . $timestamp;
        if (!file_exists($public_key_path)) {
            throw new CustomerException(Exception::FILE_NOT_SUPPORTED);
        }
        // 加载公钥
        $publicKey = openssl_pkey_get_public(file_get_contents($public_key_path));

        // 使用公钥加密参数字符串
        openssl_public_encrypt($paramString, $encrypted, $publicKey);

        // 使用SHA256进行哈希计算
        $hash = hash('sha256', $encrypted);

        // 释放公钥资源
        openssl_free_key($publicKey);

        return $hash;
    }

    /**
     * @throws CustomerException
     */
    public static function getVerifyStr(string $params, $timestamp, $public_key_path):string
    {
        $get_data = json_decode($params, true);

        return self::generateSignatureByPublic($get_data, $timestamp, $public_key_path);
    }

}