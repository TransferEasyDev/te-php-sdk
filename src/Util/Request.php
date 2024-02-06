<?php

declare(strict_types=1);
namespace Transfereasy\Pay\Util;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Transfereasy\Pay\Exception\Exception;
use Transfereasy\Pay\Exception\ServerException;

class Request
{
    /**
     * @throws ServerException
     */
    public static function get(string $url, array $data, array $header = []): array
    {
        $client = new Client();
        $ext = "?";
        foreach ($data as $key => $datum) {
            $ext.= $key ."=".$datum;
        }

        try {
            $response = $client->request('GET', $url.$ext, [
                'header' => $header
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
     * @throws ServerException
     */
    public static function post(string $url, array $data, array $header = []): array
    {
        $client = new Client();
        try {
            $response = $client->request('POST', $url, [
                'header' => $header,
                'params' => $data
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
