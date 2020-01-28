<?php

namespace app\logic;

use app\models\PrizeOrder;

interface IMoneySender
{
    public function send(int $bankAccountId, float $amount) : bool;
}