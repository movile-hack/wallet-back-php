<?php

namespace App\Service;

use App\Repository\PlacedOrderRepositoryInterface;
use App\Repository\ProductRepositoryInterface;

class ProductService implements ProductServiceInterface
{
    private $productRepository;
    private $orderRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        PlacedOrderRepositoryInterface $orderRepository
    ) {
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
    }

    public function getProductList()
    {
        $productFullList = [];
        $productList = $this->productRepository->getProductList();

        foreach ($productList as $product) {
            $productReport = $this->orderRepository->getPlacedOrdersReport($product['id']);
            $product['value'] = $productReport['summary']['amount'];
            $product['customers'] = $productReport['summary']['customers'];
            $product['average'] = $productReport['summary']['averageValue'];
            $productFullList[] = $product;
        }

        return $productFullList;
    }
}
