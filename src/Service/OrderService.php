<?php

namespace App\Service;

use App\Repository\PlacedOrderRepositoryInterface;

class OrderService implements OrderServiceInterface
{
    private $productRepository;
    public function __construct(PlacedOrderRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProductPlacedOrderSummary(string $productId)
    {
        return $this->productRepository->getPlacedOrdersSummary($productId);
    }
}
