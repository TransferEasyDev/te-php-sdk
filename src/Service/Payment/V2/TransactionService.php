<?php

declare(strict_types=1);

namespace Transfereasy\Pay\Service\Payment\V2;
use Transfereasy\Pay\Exception\CustomerException;
use Transfereasy\Pay\Exception\ServerException;
use Transfereasy\Pay\Exception\SignException;
use Transfereasy\Pay\Service\BaseService;
use Transfereasy\Pay\Util\Request;

class TransactionService extends BaseService
{

    /**
     * @param string $outTradeNo
     * @param string $merOrderNo
     * @return array
     * @throws CustomerException
     * @throws ServerException
     * @throws SignException
     */
    public function search(string $outTradeNo, string $merOrderNo): array
    {
        if (!$outTradeNo && !$merOrderNo) {
            throw new CustomerException();
        }
        $data = [
            'outTradeNo' => $outTradeNo,
            'merOrderNo' => $merOrderNo
        ];
        return Request::post($this->domain.'/V2/transaction/searchPayment' , $data, $this->config);
    }
}


