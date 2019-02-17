<?php

namespace App\Controller;

use App\Service\OrderServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrdersController
{
    private $productService;
    public function __construct(OrderServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @Route("/", methods={"GET"})
     * @return Response
     */
    public function getIndex()
    {
        return new JsonResponse(['message' => 'OK']);
    }

    /**
     * @Route("/products/{productId}/orders", methods={"GET"})
     * @param string $productId The product's placed orders
     * @return Response
     */
    public function getProductPlacedOrders(string $productId)
    {
        $summary = $this->productService->getProductPlacedOrderSummary($productId);
        return new JsonResponse($summary);
    }

    /**
     * @Route("/products/{productId}/orders", methods={"POST"})
     * @param Request $request The HTTP Request Object
     * @param string $productId The product's placed orders
     * @return Response
     */
    public function postProductOrder(Request $request, string $productId)
    {
        $order = json_decode($request->getContent(), true);
        $order['productId'] = $productId;
        $this->productService->placeOrder($order);
        return new JsonResponse($order);
    }
}
