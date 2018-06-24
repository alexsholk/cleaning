<?php

namespace App\Event;

use App\Message\OrderMessageFormatter;
use App\Model\ParamModel;
use App\SMS\SMSApi;
use App\SMS\SMSApiException;

class OrderCreatedListener
{
    protected $params;
    protected $smsApi;
    protected $messageFormatter;

    public function __construct(ParamModel $params, SMSApi $smsApi, OrderMessageFormatter $messageFormatter)
    {
        $this->params = $params;
        $this->smsApi = $smsApi;
        $this->messageFormatter = $messageFormatter;
    }

    public function onOrderCreated(OrderCreatedEvent $event)
    {
        if ($this->params->get('ORDER_CREATED_SMS_NOTIFICATION') &&
            $phone = $this->params->get('SMS_NOTIFICATION_PHONE')
        ) {
            $order = $event->getOrder();
            $message = $this->messageFormatter->formatMessage($order);
            try {
                $this->smsApi->send($phone, $message);
            } catch (SMSApiException $exception) {}
        }
    }
}