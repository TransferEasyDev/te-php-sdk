<?php

declare(strict_types=1);
namespace Transfereasy\Pay\Util;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Transfereasy\Pay\Exception\CustomerException;
use Transfereasy\Pay\Exception\Exception;
use Transfereasy\Pay\Exception\ServerException;
use Transfereasy\Pay\Exception\SignException;

class Request
{

    /**
     * @throws CustomerException
     * @throws SignException
     */
    private static function createHeader(array $data, array $config):array
    {
        $time = time();
        $header = [
            'Content-Type' => 'application/json',
            'Timestamp' => $time,
            'MerchantNO' => $config['t_merchant_no'],
            'ProductCode' => $config['t_product_code']
        ];

        $header['Signature'] = Ticket::generateSignature($data, $config['m_private_key_path'], $time);

        return $header;
    }

    /**
     * @param string $url
     * @param array $data
     * @param array $config
     * @return array
     * @throws CustomerException
     * @throws ServerException
     * @throws SignException
     */
    public static function get(string $url, array $data, array $config = []): array
    {
        $client = new Client();
        $ext = "?";
        foreach ($data as $key => $datum) {
            $ext.= $key ."=".$datum;
        }

        try {
            $response = $client->request('GET', $url.$ext, [
                'header' => self::createHeader($data, $config)
            ]);
            $code = $response->getStatusCode();
            if ($code != 200) {
                throw new ServerException(Exception::SERVER_REQUEST_ERROR);
            }
            $body = $response->getBody();
            return ResultCheck::check($body);
        } catch (GuzzleException $e) {
            throw new ServerException(Exception::SERVER_REQUEST_ERROR);
        }
    }

    /**
     * @param string $url
     * @param array $data
     * @param array $config
     * @return array
     * @throws CustomerException
     * @throws ServerException
     * @throws SignException
     */
    public static function post(string $url, array $data, array $config = []): array
    {
        $client = new Client();
        try {
            $response = $client->request('POST', $url, [
                'headers' => self::createHeader($data, $config),
                'json' => $data
            ]);

            $code = $response->getStatusCode();
            if ($code != 200) {
                throw new ServerException(Exception::SERVER_REQUEST_ERROR);
            }
            $body = $response->getBody();

            return ResultCheck::check($body);
        } catch (GuzzleException $e) {
            throw new ServerException(Exception::SERVER_REQUEST_ERROR);
        }
    }
}
