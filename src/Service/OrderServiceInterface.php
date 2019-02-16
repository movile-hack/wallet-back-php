<?php

namespace App\Service;

interface OrderServiceInterface
{
    public function getProductPlacedOrderSummary(string $productId);
}
