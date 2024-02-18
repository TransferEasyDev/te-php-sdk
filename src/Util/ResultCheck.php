<?php

namespace Transfereasy\Pay\Util;

use GuzzleHttp\Psr7\Stream;
use Transfereasy\Pay\Exception\Exception;
use Transfereasy\Pay\Exception\ServerException;

class ResultCheck
{
    /**
     * @throws ServerException
     */
    public static function check($body) : array
    {
        if (!$body instanceof Stream) {
            $content = $body;
        } else{
            $content = $body->getContents();
        }

        $get_result = json_decode($content, true);

        if ($get_result['code'] != 1000) { //出现了错误
            throw new ServerException(Exception::SERVER_REQUEST_RESULT_ERROR, $get_result['msg']);
        }
        $body->close();

        return $get_result;
    }

}