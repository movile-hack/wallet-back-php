<?php

namespace App\Controller;

use App\Service\SellServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SellController
{
    private $sellService;
    public function __construct(SellServiceInterface $sellService)
    {
        $this->sellService = $sellService;
    }

    /**
     * @Route("/sells", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function postSell(Request $request)
    {
        $sell = json_decode($request->getContent(), true);
        $result = $this->sellService->processSell($sell);
        return new JsonResponse($result);
    }
}
