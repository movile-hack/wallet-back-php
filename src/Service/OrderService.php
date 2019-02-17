<?php

namespace App\Service;

use App\Repository\OrderRepositoryInterface;
use App\Repository\TransactionRepositoryInterface;

class OrderService implements OrderServiceInterface
{
    private $productRepository;
    private $transactionRepository;
    public function __construct(
        OrderRepositoryInterface $productRepository,
        TransactionRepositoryInterface $transactionRepository
    ) {
        $this->productRepository = $productRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function getProductPlacedOrderSummary(string $productId)
    {
        return $this->productRepository->getPlacedOrdersReport($productId);
    }

    public function placeOrder(array $order)
    {
        $this->transactionRepository->reserveOrder($order);
        return $this->productRepository->insertOrder($order);
    }
}
