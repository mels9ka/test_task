<?php


namespace app\models\query;


use app\models\Prize;
use app\models\PrizeOrder;
use yii\db\ActiveQuery;
use yii\db\Expression;

class PrizeOrderQuery extends ActiveQuery
{
    public function unpaid()
    {
        return $this->joinWith('prize')
            ->andWhere(['status' => PrizeOrder::STATE_PROCESSING])
            ->andWhere(['like', 'prizes.type', Prize::TYPE_MONEY]);
    }
}