<?php

namespace App\Service;

use App\Repository\PlacedOrderRepositoryInterface;
use App\Repository\TransactionRepositoryInterface;

class SellService implements SellServiceInterface
{
    private $orderRepository;
    private $transactionRepository;
    public function __construct(
        PlacedOrderRepositoryInterface $orderRepository,
        TransactionRepositoryInterface $transactionRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function processSell(array $sell)
    {
        $productId = $sell['productId'];
        $value = $sell['value'];
        $sellerId = $sell['sellerId'];
        $orderList = $this->orderRepository->getOrderList($productId, $value);

        foreach ($orderList as $order) {
            $buyer = $order['buyerId'];
            $this->transactionRepository->processTransaction($sellerId, $buyer, $value);
        }
        return $orderList;
    }
}
