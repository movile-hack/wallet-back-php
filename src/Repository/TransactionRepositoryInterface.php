<?php

namespace App\Repository;

interface TransactionRepositoryInterface
{
    public function reserveOrder(array $order);
    public function processTransaction(string $seller, string $buyer, string $value);
}
