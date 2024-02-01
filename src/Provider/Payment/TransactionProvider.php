<?php

declare(strict_types=1);

namespace Transfereasy\Pay\Provider\Payment;


use Ramsey\Collection\Collection;
use Transfereasy\Pay\Pay\Contract\Payment\V1\TransactionService;

class TransactionProvider implements TransactionService {

    public function walletSelect(): Collection
    {
        // TODO: Implement walletSelect() method.
    }

    public function rateSelect(): Collection
    {
        // TODO: Implement rateSelect() method.
    }

    public function payment(): Collection
    {
        // TODO: Implement payment() method.
    }

    public function findOne(string $no): Collection
    {
        // TODO: Implement findOne() method.
    }

    public function cancel(string $no): Collection
    {
        // TODO: Implement cancel() method.
    }

    public function refund(array $param): Collection
    {
        // TODO: Implement refund() method.
    }

    public function findRefund(string $no): Collection
    {
        // TODO: Implement findRefund() method.
    }

    public function billDownload(string $no)
    {
        // TODO: Implement billDownload() method.
    }
}