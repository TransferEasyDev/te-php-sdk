<?php

declare(strict_types=1);

namespace Transfereasy\Pay\Pay\Contract;
use http\Encoding\Stream;
use Ramsey\Collection\Collection;

interface StocksService
{
    public function createStocks(array $param): Collection;

    public function stockCodes(array $param): Collection;

    public function stockSend(array $param): Collection;

    public function writeOffCoupon(array $param): Collection;

    public function findStock(string $stock_id, string $settle_currency): Collection;

    public function findStockByCondition(array $param): Collection;

    public function editStock(array $param): Collection;

    public function editBatchStock(array $param): Collection;

    public function editStockBasic(array $param): Collection;

    public function deactivateStock(array $param): Collection;

    public function findCoupon(array $param): Collection;

    public function setStockNotifyUrl(string $settle_currency, string $notify_url): Collection;

    public function findStockNotifyUrl(string $settle_currency): string;

    public function uploadStockPic(array $param): Collection;

    public function stockShipBind(array $param): Collection;
}
