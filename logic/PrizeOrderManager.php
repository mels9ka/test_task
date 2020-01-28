<?php


namespace app\logic;


use app\models\Prize;
use app\models\PrizeOrder;
use app\models\User;
use yii\db\Exception;
use yii\helpers\Json;
use yii\helpers\Url;

class PrizeOrderManager implements IPrizeOrderManager
{
    /** @var PrizeOrder */
    private $prizeOrder;

    /** @var float */
    private $convertRatio = 2.5;

    /** @var MoneySender */
    private $moneySender;

    public function __construct(PrizeOrder $prizeOrder)
    {
        $this->prizeOrder = $prizeOrder;
        $this->moneySender = new MoneySender();
    }

    public function setConvertRatio(float $convertRatio)
    {
        $this->convertRatio = $convertRatio;
    }

    /**
     * Make JSON response for client modal window;
     *
     ** @return string the encoding result.
     * @throws \Exception if prize type is not constant;
     */

    public function makeJsonResponseForNewOrder() : string
    {
        $result = [];
        $result['id'] = $this->prizeOrder->id;
        $result['name'] = $this->prizeOrder->prize->prizeName->name;
        $result['count'] = $this->prizeOrder->count;
        $result['decline_text'] = 'Decline';

        switch ($this->prizeOrder->prize->type)
        {
            case Prize::TYPE_LOYALTY_POINTS:
                $result['accept_text'] = 'Add points to balance';
                break;

            case Prize::TYPE_THING:
                $result['accept_text'] = 'Send me prize by post';
                break;

            case Prize::TYPE_MONEY:
                $possibleLoyaltyPoints = (int) ($this->prizeOrder->count * $this->convertRatio);
                $result['accept_text'] = 'Send to my bank account';
                $result['convert_text'] = 'Convert to '.$possibleLoyaltyPoints.' loyalty points';
                break;

            default:
                throw new \Exception('Undefined prize type!');
                break;
        }

        return Json::encode($result);
    }

    /**
     * Processing accepted prize order;
     *
     * @return bool
     * @throws \Exception if prize type is not constant
     * or cannot validate and update PrizeOrder record;
     */
    public function acceptOrder() : bool
    {
        if($this->prizeOrder && $this->prizeOrder->status == PrizeOrder::STATE_NEW)
        {
            $this->prizeOrder->status = PrizeOrder::STATE_PROCESSING;

            switch ($this->prizeOrder->prize->type)
            {
                case Prize::TYPE_LOYALTY_POINTS:
                    if($this->acceptLoyaltyPointsOrder())
                        $this->prizeOrder->status = PrizeOrder::STATE_COMPLETED;
                    break;

                case Prize::TYPE_THING:
                    if($this->acceptThingOrder())
                        $this->prizeOrder->status = PrizeOrder::STATE_COMPLETED;
                    break;

                case Prize::TYPE_MONEY:
                    if($this->acceptMoneyOrder())
                        $this->prizeOrder->status = PrizeOrder::STATE_COMPLETED;
                    break;

                default:
                    throw new \Exception('Undefined prize type!');
                    break;
            }

            if(!$this->prizeOrder->save())
                throw new \Exception('Cannot update prize order record');

            return true;
        }

        return false;
    }

    public function declineOrder() : bool
    {
        if($this->prizeOrder)
        {
            $this->prizeOrder->status = PrizeOrder::STATE_DECLINED;
            $prizeRecord = $this->prizeOrder->prize;

            if($prizeRecord->type != Prize::TYPE_LOYALTY_POINTS)
                $prizeRecord->count += $this->prizeOrder->count;

            if(!$prizeRecord->save())
                throw new \Exception('Cannot update prize record');

            if(!$this->prizeOrder->save())
                throw new \Exception('Cannot update prize order record');

            return true;
        }

        return false;
    }

    private function acceptLoyaltyPointsOrder() : bool
    {
        $user = $this->prizeOrder->user;
        $user->loyalty_points += $this->prizeOrder->count;

        if(!$user->save())
            throw new \Exception('Cannot update user record!');

        return true;
    }

    private function acceptMoneyOrder()
    {
        $result = true;

        // Convert won money in loyalty points;
        if($this->prizeOrder->is_convert)
        {
            $possibleLoyaltyPoints = (int) ($this->prizeOrder->count * $this->convertRatio);
            $user = $this->prizeOrder->user;
            $user->loyalty_points += $possibleLoyaltyPoints;

            if(!$user->save())
                throw new \Exception('Cannot update user record!');

            $prizeRecord = $this->prizeOrder->prize;
            $prizeRecord->count += $this->prizeOrder->count;

            if(!$prizeRecord->save())
                throw new \Exception('Cannot update prize record');
        }
        else
        {
            // Send money to user's bank account;
            /*$bankAccountId = $this->prizeOrder->user->bank_account_id;
            $amount = $this->prizeOrder->count;
            $result = $this->moneySender->send($bankAccountId, $amount);*/

            $result = false; // For make data for console command;
        }

        return $result;
    }

    private function acceptThingOrder() : bool
    {
        // Send thing by post;
        return true;
    }
}