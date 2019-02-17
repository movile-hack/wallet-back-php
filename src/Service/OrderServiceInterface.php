<?php

namespace App\Service;

interface OrderServiceInterface
{
    public function placeOrder(array $order);
    public function getProductPlacedOrderSummary(string $productId);
}
