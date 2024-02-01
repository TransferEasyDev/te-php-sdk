<?php

declare(strict_types=1);

namespace Transfereasy\Pay\Pay\Contract\Payment\V1;
use Ramsey\Collection\Collection;

interface TransactionService
{
    public function walletSelect(): Collection;

    public function rateSelect(): Collection;

    public function payment(): Collection;

    public function findOne(string $no): Collection;

    public function cancel(string $no): Collection;

    public function refund(array $param) : Collection;

    public function findRefund(string $no): Collection;

    public function billDownload(string $no);


}


