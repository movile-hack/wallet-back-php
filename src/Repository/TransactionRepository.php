<?php

namespace App\Repository;

use GuzzleHttp\Client;

class TransactionRepository implements TransactionRepositoryInterface
{
    private $zoopClient;
    const BC_SELLER_ID = '074e381d9a0c46a790991ac65eae384c';
    const MARKETPLACE_ID = '3249465a7753536b62545a6a684b0000';
    public function __construct(Client $zoopClient)
    {
        $this->zoopClient = $zoopClient;
    }

    public function reserveOrder(array $order)
    {
        $headers = [
            'Authorization' => 'Basic enBrX3Rlc3Rfb2dtaTNUSm5WMzNVRGxqZE40bjhhUml0Og==',
            'Content-Type' => 'application/json'
        ];
        $buyerId = $order['buyerId'];

        $valueCents = ($order['maxValue'] ?? 0);
        $order = [
            "amount" => $valueCents,
            "currency" => "BRL",
            "description" => "Ordem de compra",
            "on_behalf_of" => self::BC_SELLER_ID,
            "customer" => $buyerId,
            "payment_type" => "credit",
            "reference_id" => md5(uniqid(rand(), true))
        ];
        $creditCardChargeOptions = ['json' => $order, 'headers' => $headers];
        $uri = sprintf('/v1/marketplaces/%s/transactions', self::MARKETPLACE_ID);
        $this->zoopClient->request('POST', $uri, $creditCardChargeOptions);


        $peer2peerUri = sprintf(
            '/v2/marketplaces/%s/transfers/%s/to/%s',
            self::MARKETPLACE_ID,
            self::BC_SELLER_ID,
            $buyerId
        );

        $peer2peerTransfer = [
            'amount' => $valueCents,
            'description' => "credit"
        ];

        $peer2peerTransferOptions = ['json' => $peer2peerTransfer, 'headers' => $headers];
        $this->zoopClient->request('POST', $peer2peerUri, $peer2peerTransferOptions);
    }
    public function processTransaction(string $seller, string $buyer, string $value)
    {
    }
}
