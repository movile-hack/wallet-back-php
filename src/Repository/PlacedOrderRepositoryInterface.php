<?php

namespace App\Repository;

interface PlacedOrderRepositoryInterface
{
    public function getPlacedOrdersSummary(string $productId);
}
