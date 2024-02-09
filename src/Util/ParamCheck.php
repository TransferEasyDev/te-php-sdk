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
        if (is_array($get_result) && count($get_result) > 0) {
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
        $base_data = ['m_private_key_path', 't_public_key_path', 't_merchant_no', 't_product_code'];
        $param_keys = array_keys($param);
        $missing_keys = array_diff($base_data, $param_keys);

        if (empty($missing_keys)) return true;

        return $missing_keys;
    }


}