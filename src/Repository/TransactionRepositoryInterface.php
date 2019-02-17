<?php

namespace App\Repository;

interface TransactionRepositoryInterface
{
    public function processTransaction(string $seller, string $buyer, string $value);
}
