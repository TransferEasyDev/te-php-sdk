<?php

declare(strict_types=1);

namespace Transfereasy\Pay\Pay\Contract;
use http\Encoding\Stream;
use Ramsey\Collection\Collection;


interface CustomsService
{
    public function findDeclare(array $param): Collection;

    public function declare(array $param): Collection;

    public function redeclare(array $param): Collection;
}