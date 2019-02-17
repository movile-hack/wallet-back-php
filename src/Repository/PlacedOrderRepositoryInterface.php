<?php

namespace App\Repository;

interface PlacedOrderRepositoryInterface
{
    public function insertOrder(array $order);
    public function getPlacedOrdersReport(string $productId);
    public function getOrderList(string $productId, float $value);
}
