<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController
{

    /**
     * @Route("/", methods={"GET"})
     * @return Response
     */
    public function getIndex()
    {
        return new JsonResponse(['message' => 'OK']);
    }
}
