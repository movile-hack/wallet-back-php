<?php

namespace App\Repository;

interface OrderRepositoryInterface
{
    public function insertOrder(array $order);
    public function getPlacedOrdersReport(string $productId);
    public function getOrderList(string $productId, float $value);
    public function updateOrderToExecuted(array $order);
}
