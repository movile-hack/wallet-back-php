<?php

namespace App\Repository;

use GuzzleHttp\Client;

class NotificationRepository implements NotificationRepositoryInterface
{
    private $notificationClient;
    public function __construct(Client $notificationClient)
    {
        $this->notificationClient = $notificationClient;
    }

    public function notifyOrderExecutedToBuyer(array $order)
    {
        $productId = $order['productId'];
        $productMap = [
            '12365419' => 'Macbook',
            '52967376' => 'Iphone',
            '52901919' => 'Playstation 4'
        ];


        $message = sprintf("Rafael, seu %s está a caminho e você ainda economizou!!!!", $productMap[$productId]);

        $options = [
            'json' => [
                'to' => '5511985642465',
                'message' => $message,
                'carrier' => 'TIM'
            ],
            'headers' => ['Access-key' => 'ek-78945196321']
        ];
        $this->notificationClient->request('POST', '', $options);
    }

    public function notifyOrderExecutedToSeller(array $sell)
    {
        //Notificação desabilitada para evitar uso desnecessário da API
        $message = sprintf("Rafael, você conseguiu vender muito, parabéns");

        $options = [
            'json' => [
                'to' => '5511985642465',
                'message' => $message,
                'carrier' => 'TIM'
            ],
            'headers' => ['Access-key' => 'ek-78945196321']
        ];
        //$this->notificationClient->request('POST', '', $options);
    }
}
