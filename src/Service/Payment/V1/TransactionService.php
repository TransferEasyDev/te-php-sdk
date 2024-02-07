<?php

declare(strict_types=1);

namespace Transfereasy\Pay\Service\Payment\V1;

use Transfereasy\Pay\Exception\CustomerException;
use Transfereasy\Pay\Exception\Exception;
use Transfereasy\Pay\Exception\ServerException;
use Transfereasy\Pay\Service\BaseService;
use Transfereasy\Pay\Util\Request;

class TransactionService extends BaseService
{

    /**
     * @param $params string outTradeNo
     * @return array
     * @throws CustomerException
     * @throws ServerException
     */
    public function cancel(string $params): array
    {
        if ($params == "" || $params == null) {
            throw new CustomerException(Exception::PARAMS_IS_NOT_ENOUGH, "outTradeNo no can not empty");
        }

        $route = "/V1/transaction/closePayment";

        return Request::post($this->domain.$route, ['outTradeNo' => $params], $this->config);
    }
}


