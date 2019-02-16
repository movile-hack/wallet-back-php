<?php


namespace App\Repository;

use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;

class PlacedOrderRepository implements PlacedOrderRepositoryInterface
{
    private $collection;
    public function __construct(Client $mongoClient)
    {
        $this->collection = $mongoClient->selectCollection('wallet', 'placedOrders');
    }

    public function getPlacedOrdersSummary(string $productId)
    {

        $aggregationPipeline = [
            ['$sort' =>  ['maxValue' => 1]],
            ['$match' => [
                'productId' => $productId, 'expirationDate' => ['$gte' => new UTCDateTime(strtotime("now"))]
            ]],
            ['$group' => ['_id' => '$maxValue', 'count' => ['$sum' => 1]]]
        ];

        $aggregation = $this->collection->aggregate($aggregationPipeline);

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
