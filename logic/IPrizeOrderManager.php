<?php

namespace app\logic;

use app\models\PrizeOrder;

interface IPrizeOrderManager
{
    public function makeJsonResponseForNewOrder() : string;
    public function acceptOrder() : bool;
    public function declineOrder() : bool;
    public function setConvertRatio(float $convertRatio);
}