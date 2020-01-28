<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\logic\MoneySender;
use app\models\PrizeOrder;
use phpDocumentor\Reflection\Types\Integer;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Manages prize orders;
 *
 */
class PrizeOrderController extends Controller
{
    /**
     * This command send money to user's bank accounts;
     * This command can be used as follows on command line:
     * ```
     * yii yii prize-order/pay [portion]
     * @param $portion int
     * @return int Exit code
     * @throws \Exception if cannot update prize order record
     */
    public function actionPay(int $portion = 1)
    {
        $prizeOrders = PrizeOrder::find()->unpaid()->with('user')->limit($portion)->all();
        $countOrders = count($prizeOrders);
        echo "Found $countOrders not processed orders\n";

        if($countOrders > 0)
        {
            $countProcessedOrders = 0;
            $moneySender = new MoneySender();

            foreach ($prizeOrders as $order)
            {
                $bankAccountId = $order->user->bank_account_id;
                $amount = $order->count;

                if($moneySender->send($bankAccountId, $amount))
                {
                    $order->status = PrizeOrder::STATE_COMPLETED;

                    if(!$order->save())
                        throw new \Exception('Cannot update prize order record');

                    $countProcessedOrders ++;
                }
            }

            echo "$countProcessedOrders from $countOrders orders processed successful\n";
        }

        return ExitCode::OK;
    }
}
