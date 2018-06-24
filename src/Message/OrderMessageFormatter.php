<?php

namespace App\Message;

use App\Entity\Order;

class OrderMessageFormatter
{
    public function formatMessage(Order $order): string
    {
        $number = $order->getId();
        $date = $order->getDatetime() ? $order->getDatetime()->format('d.m.Y H:i') : '';
        $message = sprintf('Заказ #%s на уборку %s. ', $number, $date);
        $message .= $order->getAddress() . '.';
        return $message;
    }
}