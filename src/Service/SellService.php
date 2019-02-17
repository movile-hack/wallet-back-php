<?php

namespace App\Service;

use App\Repository\NotificationRepositoryInterface;
use App\Repository\OrderRepositoryInterface;
use App\Repository\TransactionRepositoryInterface;
use MongoDB\Model\BSONDocument;

class SellService implements SellServiceInterface
{
    private $orderRepository;
    private $transactionRepository;
    private $notificationRepository;
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        TransactionRepositoryInterface $transactionRepository,
        NotificationRepositoryInterface $notificationRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->transactionRepository = $transactionRepository;
        $this->notificationRepository = $notificationRepository;
    }

    public function processSell(array $sell)
    {
        $productId = $sell['productId'];
        $value = $sell['value'];
        $sellerId = $sell['sellerId'];
        $orderList = $this->orderRepository->getOrderList($productId, $value);
        $itemsSold = count($orderList);
        $sell['itemsSold'] = $itemsSold;

        /** @var BSONDocument $order */
        foreach ($orderList as $order) {
            $order = iterator_to_array($order);
            $order['sellerId'] = $sellerId;
            $buyer = $order['buyerId'];
            $this->transactionRepository->processTransaction($sellerId, $buyer, $value);
            $this->orderRepository->updateOrderToExecuted($order);
        }
        /*
         * A notificação do buyer deve ser executada dentro do for,
         * entretanto, para evitar consumir a cota da API da Wavy,
         * será executada apenas uma vez fora do for com a última ordem
         */
        $this->notificationRepository->notifyOrderExecutedToBuyer($order);
        $this->notificationRepository->notifyOrderExecutedToSeller($sell);
        return $orderList;
    }
}
