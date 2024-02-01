<?php

declare(strict_types=1);

namespace Transfereasy\Pay\Pay\Contract\Payment\V2;
use Ramsey\Collection\Collection;

interface TransactionService
{
    public function findOne(string $no, string $out_trade_no): Collection;
}


