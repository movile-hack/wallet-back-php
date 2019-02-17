<?php

namespace App\Repository;

interface NotificationRepositoryInterface
{
    public function notifyOrderExecutedToBuyer(array $order);

    public function notifyOrderExecutedToSeller(array $sell);
}
