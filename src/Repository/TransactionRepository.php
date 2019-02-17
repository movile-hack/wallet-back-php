<?php

namespace App\Repository;

use GuzzleHttp\Client;

class TransactionRepository implements TransactionRepositoryInterface
{
    private $zoopClient;
    const ZOOP_HEADERS = [
        'Authorization' => 'Basic enBrX3Rlc3Rfb2dtaTNUSm5WMzNVRGxqZE40bjhhUml0Og==',
        'Content-Type' => 'application/json'
    ];
    const BC_SELLER_ID = '074e381d9a0c46a790991ac65eae384c';
    const MARKETPLACE_ID = '3249465a7753536b62545a6a684b0000';
    public function __construct(Client $zoopClient)
    {
        $this->zoopClient = $zoopClient;
    }

    public function reserveOrder(array $order)
    {
        $buyerId = $order['buyerId'];
        $valueCents = ($order['maxValue'] ?? 0);

        $this->chargeOnCreditCard($buyerId, $valueCents);
        $this->transferAmountPeerToPeer(self::BC_SELLER_ID, $buyerId, $valueCents);
    }

    private function chargeOnCreditCard(string $buyerId, int $ammount)
    {
        $order = [
            "amount" => $ammount,
            "currency" => "BRL",
            "description" => "Ordem de compra",
            "on_behalf_of" => self::BC_SELLER_ID,
            "customer" => $buyerId,
            "payment_type" => "credit",
            "reference_id" => md5(uniqid(rand(), true))
        ];
        $creditCardChargeOptions = ['json' => $order, 'headers' => self::ZOOP_HEADERS];
        $uri = sprintf('/v1/marketplaces/%s/transactions', self::MARKETPLACE_ID);
        $this->zoopClient->request('POST', $uri, $creditCardChargeOptions);
    }

    private function transferAmountPeerToPeer(string $owner, string $receiver, int $amount)
    {
        $peer2peerUri = sprintf(
            '/v2/marketplaces/%s/transfers/%s/to/%s',
            self::MARKETPLACE_ID,
            $owner,
            $receiver
        );

        $peer2peerTransfer = [
            'amount' => $amount,
            'description' => "credit"
        ];

        $peer2peerTransferOptions = ['json' => $peer2peerTransfer, 'headers' => self::ZOOP_HEADERS];
        $this->zoopClient->request('POST', $peer2peerUri, $peer2peerTransferOptions);
    }

    public function processTransaction(string $seller, string $buyer, string $value)
    {
        $this->transferAmountPeerToPeer($buyer, $seller, $value);
    }
}
