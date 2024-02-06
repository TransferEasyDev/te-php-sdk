<?php

namespace Transfereasy\Pay\Util;

use Transfereasy\Pay\Exception\Exception;
use Transfereasy\Pay\Exception\ServerException;

class ResultCheck
{
    /**
     * @throws ServerException
     */
    public static function check($body) : array
    {
        $getResult = json_decode($body, true);
        if ($body['code'] != 1000) { //出现了错误
            throw new ServerException(Exception::SERVER_REQUEST_RESULT_ERROR, $getResult['message']);
        }

        return $body['data'];
    }
}