<?php

namespace App\Controller;

use App\Service\ProductServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController
{
    private $productService;
    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @Route("/products", methods={"GET"})
     * @return Response
     */
    public function getProductList()
    {
        $productList = $this->productService->getProductList();
        return new JsonResponse($productList);
    }
}
