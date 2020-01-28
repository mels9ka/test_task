<?php

namespace app\logic;

use app\models\PrizeOrder;

interface IPrizeMaker
{
    public function makePrize() : PrizeOrder;
    public function setLoyaltyPointsRange(int $min, int $max);
    public function setMoneyRange(int $min, int $max);
}