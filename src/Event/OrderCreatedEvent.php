<?php

namespace App\Event;

use App\Entity\Order;
use Symfony\Component\EventDispatcher\Event;

class OrderCreatedEvent extends Event
{
    const NAME = 'order.created';

    /** @var Order */
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function getOrder()
    {
        return $this->order;
    }
}