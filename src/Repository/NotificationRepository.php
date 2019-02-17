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
        $message = sprintf("Rafael, você conseguiu comprar barato!");

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

    public function notifyOrderExecutedToSeller(array $sell)
    {
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
