<?php
declare(strict_types=1);
namespace Transfereasy\Pay\Util;
use Transfereasy\Pay\Exception\CustomerException;
use Transfereasy\Pay\Exception\Exception;

class ParamCheck
{
    /**
     * @throws CustomerException
     */
    public static function check($params = [], $method="config")
    {
        $get_result = self::{$method}($params);
        if (count($get_result) > 0) {
            $str = '';
            foreach ($get_result as $params) {
                $str .= $params . ',';
            }
            $str .= ' not enough';
            throw new CustomerException(Exception::PARAMS_IS_NOT_ENOUGH, $str);
        }
    }

    private static function config($param)
    {
        $base_data = ['', '', ''];
        $param_keys = array_keys($param);
        $result = array_diff($param_keys, $base_data);
        if (empty($result)) return true;

        return $result;
    }


}