<?php

namespace Transfereasy\Pay\Util;

use Transfereasy\Pay\Exception\CustomerException;
use Transfereasy\Pay\Exception\Exception;
use Transfereasy\Pay\Exception\SignException;

class Ticket
{

    /**
     * @param array $data
     * @param $timestamp
     * @return string
     * @throws CustomerException
     */
    private static function dealParams(array $data, $timestamp): string
    {
        if (empty($data)) {
            throw new CustomerException();
        }

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
        return http_build_query($data).','.$timestamp;
    }

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
        $signData = self::dealParams($data, $timestamp);

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
     * @param $sign_str
     * @param $public_key_path
     * @return bool
     * @throws CustomerException
     */
    private static function generateSignatureByPublic($data, $timestamp, $sign_str, $public_key_path):bool
    {
        $param_str = self::dealParams($data, $timestamp);

        if (!file_exists($public_key_path)) {
            throw new CustomerException(Exception::FILE_NOT_SUPPORTED);
        }
        // 加载公钥
        $public_key = openssl_pkey_get_public(file_get_contents($public_key_path));

        // 使用公钥解密参数字符串
        $is_valid = openssl_verify($param_str, base64_decode($sign_str), $public_key, OPENSSL_ALGO_SHA256);

        // 释放公钥资源
        openssl_free_key($public_key);

        return $is_valid === 1;
    }

    /**
     * @throws CustomerException
     */
    public static function getVerifyStr(string $params, $timestamp, $sign_str, $public_key_path):bool
    {
        $get_data = json_decode($params, true);

        return self::generateSignatureByPublic($get_data, $timestamp, $sign_str, $public_key_path);
    }

}