<?php

namespace Transfereasy\Pay\Service;

class Ticket
{
    /**
     * 验证签名
     * @param string $message
     * @return void
     */
    public static function verify(string $message) :bool
    {
        return false;
    }

    /**
     * 加签名
     * @param array $data
     * @return void
     */
    public static function add(array $data)
    {

    }
}