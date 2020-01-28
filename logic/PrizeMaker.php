<?php


namespace app\logic;


use app\models\Prize;
use app\models\PrizeOrder;
use yii\db\Expression;

class PrizeMaker implements IPrizeMaker
{
    /** @var int  */
    private $userId;

    private $moneyMin = 2;
    private $moneyMax = 10;

    private $pointsMin = 5;
    private $pointsMax = 20;

    /**
     * @param int $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Set range for money prize;
     * @param int $min;
     * @param int $max;
     */
    public function setMoneyRange(int $min, int $max)
    {
        $this->moneyMin = $min;
        $this->moneyMax = $max;
    }

    /**
     * Set range for loyalty points prize;
     * @param int $min;
     * @param int $max;
     */
    public function setLoyaltyPointsRange(int $min, int $max)
    {
        $this->pointsMin = $min;
        $this->pointsMax = $max;
    }

    /** Make random prize
     * @return PrizeOrder;
     * @throws \Exception cannot save record;
     */
    public function makePrize(): PrizeOrder
    {
        $prizeRecord = Prize::find()->random()->limit(1)->one();
        $prizeOrder = $this->generatePrizeOrder($prizeRecord);

        if($prizeRecord->type != Prize::TYPE_LOYALTY_POINTS)
        {
            $prizeRecord->count -= $prizeOrder->count;
            if(!$prizeRecord->save())
                throw new \Exception('Cannot save updated prize record');
        }

        if(!$prizeOrder->save())
            throw new \Exception('Cannot save new prize order');

        return $prizeOrder;
    }

    private function generatePrizeOrder(Prize $prizeRecord) : PrizeOrder
    {
        $prizeOrder = new PrizeOrder();
        $prizeOrder->user_id = $this->userId;
        $prizeOrder->prize_id = $prizeRecord->id;
        $prizeOrder->status = PrizeOrder::STATE_NEW;

        switch ($prizeRecord->type)
        {
            case Prize::TYPE_LOYALTY_POINTS:
                $prizeOrder->count = $this->generateCountLoyalityPoints($prizeRecord);
                break;

            case Prize::TYPE_MONEY:
                $prizeOrder->count = $this->generateCountMoney($prizeRecord);
                break;

            case Prize::TYPE_THING:
                $prizeOrder->count = 1;
                break;

            default:
                throw new \Exception('Undefined prize type!');
                break;
        }

        return $prizeOrder;
    }

    private function generateCountLoyalityPoints(Prize $prizeRecord) : int
    {
        return rand($this->pointsMin, $this->pointsMax);
    }

    private function generateCountMoney(Prize $prizeRecord) : int
    {
        $min = $this->moneyMin < $prizeRecord->count ? $this->moneyMin : $prizeRecord->count;
        $max = $this->moneyMax < $prizeRecord->count ? $this->moneyMax : $prizeRecord->count;
        return rand($min, $max);
    }

}