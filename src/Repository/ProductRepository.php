<?php

namespace App\Repository;

use MongoDB\Client;

class ProductRepository implements ProductRepositoryInterface
{
    private $collection;
    public function __construct(Client $mongoClient)
    {
        $this->collection = $mongoClient->selectCollection('wallet', 'products');
    }

    public function getProductList(): array
    {
        $cursor =  $this->collection->find([], ['projection' => ['_id' => 0]]);
        return $cursor->toArray();
    }

    public function updateOrdersToExecuted(array $orderIdList)
    {
//        $filter = ['']
//        $this->collection->updateMany()
    }
}
