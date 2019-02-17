<?php

namespace App\Repository;

use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;

class OrderRepository implements OrderRepositoryInterface
{
    private $placedOrderCollection;
    private $executedOrderCollection;
    public function __construct(Client $mongoClient)
    {
        $this->placedOrderCollection = $mongoClient->selectCollection('wallet', 'placedOrders');
        $this->executedOrderCollection = $mongoClient->selectCollection('wallet', 'executedOrders');
    }

    public function insertOrder(array $order)
    {
        $order['id'] = md5(uniqid(rand(), true));
        $order['expirationDate'] = new UTCDateTime(strtotime($order['expirationDate'])* 1000);
        $this->placedOrderCollection->insertOne($order);
    }

    public function getOrderList(string $productId, float $value) : array
    {
        $filter = ['productId' => $productId, 'maxValue' => ['$gte' => $value]];
        $options = ['projection' => ['_id' => 0]];
        $cursor = $this->placedOrderCollection->find($filter, $options);

        return $cursor->toArray();
    }

    public function updateOrderToExecuted(array $order)
    {
        $this->placedOrderCollection->deleteOne(['id' => $order['id']]);
        $this->executedOrderCollection->insertOne($order);
    }

    public function getPlacedOrdersReport(string $productId)
    {

        $aggregationPipeline = [
            ['$sort' =>  ['maxValue' => 1]],
            ['$match' => [
                'productId' => $productId, 'expirationDate' => ['$gte' => new UTCDateTime(strtotime("now"))]
            ]],
            ['$group' => ['_id' => '$maxValue', 'count' => ['$sum' => 1]]],
            ['$sort' =>  ['_id' => -1]]
        ];

        $aggregation = $this->placedOrderCollection->aggregate($aggregationPipeline);

        $report = [];
        $totalCustomers = 0;
        $amount = 0;
        foreach ($aggregation as $item) {
            $value = $item['_id'];
            $customers = $item['count'] ?? 0;
            $totalCustomers += $customers;
            $amount += (float) $value * $customers;
            $report[] = ['value' => $value, 'customers' =>  $totalCustomers ];
        }

        $averageValue = $totalCustomers == 0 ? 0 : floatval($amount / $totalCustomers);
        $summary = [
            'report' => $report,
            'summary' => [
                'customers' => $totalCustomers,
                'amount' => $amount,
                'averageValue' => $averageValue
            ]
        ];
        return $summary;
    }
}
