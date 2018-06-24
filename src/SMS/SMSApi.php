<?php

namespace App\SMS;

use GuzzleHttp\Client;

class SMSApi
{
    const API_URL = 'https://userarea.sms-assistent.by/api/v1';
    const DEFAULT_SENDER = 'TEST-assist';

    const ERROR_INSUFFICIENT_FUNDS       = -1;
    const ERROR_AUTHENTICATION           = -2;
    const ERROR_MISSING_TEXT             = -3;
    const ERROR_INVALID_RECIPIENT_NUMBER = -4;
    const ERROR_INVALID_SENDER_VALUE     = -5;
    const ERROR_MISSING_LOGIN            = -6;
    const ERROR_MISSING_PASSWORD         = -7;
    const ERROR_SERVICE_UNAVAILABLE      = -10;
    const ERROR_INVALID_MESSAGE_ID       = -11;
    const ERROR_OTHER                    = -12;
    const ERROR_BLOCKED                  = -13;
    const ERROR_TIME_LIMITS              = -14;
    const ERROR_INVALID_SEND_DATE        = -15;

    protected static $errorMessages = [
        self::ERROR_INSUFFICIENT_FUNDS       => 'Недостаточно средств',
        self::ERROR_AUTHENTICATION           => 'Неправильный логин или пароль (ошибка при аутентификации)',
        self::ERROR_MISSING_TEXT             => 'Отсутствует текст сообщения',
        self::ERROR_INVALID_RECIPIENT_NUMBER => 'Некорректное значение номера получателя',
        self::ERROR_INVALID_SENDER_VALUE     => 'Некорректное значение отправителя сообщения',
        self::ERROR_MISSING_LOGIN            => 'Отсутствует логин',
        self::ERROR_MISSING_PASSWORD         => 'Отсутствует пароль',
        self::ERROR_SERVICE_UNAVAILABLE      => 'Сервис временно недоступен',
        self::ERROR_INVALID_MESSAGE_ID       => 'Некорректное значение ID сообщения',
        self::ERROR_OTHER                    => 'Другая ошибка',
        self::ERROR_BLOCKED                  => 'Заблокировано',
        self::ERROR_TIME_LIMITS              => 'Запрос не укладывается в ограничения по времени на отправку SMS',
        self::ERROR_INVALID_SEND_DATE        => 'Некорректное значение даты отправки рассылки',
    ];

    const SMS_STATUS_QUEUED    = 'Queued';
    const SMS_STATUS_SENT      = 'Sent';
    const SMS_STATUS_DELIVERED = 'Delivered';
    const SMS_STATUS_EXPIRED   = 'Expired';
    const SMS_STATUS_REJECTED  = 'Rejected';
    const SMS_STATUS_UNKNOWN   = 'Unknown';
    const SMS_STATUS_FAILED    = 'Failed';

    protected static $smsStatuses = [
        self::SMS_STATUS_QUEUED    => 'В очереди',
        self::SMS_STATUS_SENT      => 'Отправлено',
        self::SMS_STATUS_DELIVERED => 'Доставлено',
        self::SMS_STATUS_EXPIRED   => 'Просрочено',
        self::SMS_STATUS_REJECTED  => 'Не доставлено',
        self::SMS_STATUS_UNKNOWN   => 'Неизвестен',
        self::SMS_STATUS_FAILED    => 'Не отправлено',
    ];

    protected $login;
    protected $password;
    protected $client;

    public function __construct(string $login, string $password)
    {
        $this->login = $login;
        $this->password = $password;
        $this->client = new Client();
    }

    /**
     * @param $recipient
     * @param $message
     * @param $sender
     * @param \DateTimeInterface|null $dateSend
     * @param null $validityPeriod
     * @return string
     * @throws SMSApiException
     */
    public function send($recipient, $message, $sender = self::DEFAULT_SENDER, \DateTimeInterface $dateSend = null, $validityPeriod = null)
    {
        if (!is_null($dateSend)) {
            $dateSend = $dateSend->format('YmdHi');
        }

        return $this->callUrl('/send_sms/plain', [
            'recipient' => $recipient,
            'message' => $message,
            'sender' => $sender,
            'date_send' => $dateSend,
            'validity_period' => $validityPeriod,
        ]);
    }

    /**
     * @param $messageId
     * @return string
     * @throws SMSApiException
     */
    public function getStatus($messageId)
    {
        return $this->callUrl('/statuses/plain', ['id' => $messageId]);
    }

    /**
     * @return string
     * @throws SMSApiException
     */
    public function getCredits()
    {
        return $this->callUrl('/credits/plain');
    }

    /**
     * @param $url
     * @param array $data
     * @return string
     * @throws SMSApiException
     */
    protected function callUrl($url, $data = [])
    {
        $data += [
            'user' => $this->login,
            'password' => $this->password,
        ];

        $url = self::API_URL . $url . '?' . http_build_query($data);

        $response = $this->client->get($url);
        $content = $response->getBody()->getContents();
        if ($content < 0) {
            $message = self::$errorMessages[$content] ?? 'Unknown error';
            throw new SMSApiException($message, $content);
        }

        return $content;
    }
}