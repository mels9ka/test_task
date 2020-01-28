<?php

namespace app\logic;




use yii\httpclient\Client;

class MoneySender implements IMoneySender
{
    /** @var  Client*/
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function send(int $bankAccountId, float $amount): bool
    {
        $url = sprintf('http://localhost:80/api/bank/update?id=%d', $bankAccountId);
        $data = ['money' => $amount];

        $response = $this->client->createRequest()
            ->setMethod('PUT')
            ->setUrl($url)
            ->setData($data)
            ->send();

        return $response->isOk;
    }
}