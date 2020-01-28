<?php


namespace app\models\query;


use app\models\Prize;
use yii\db\ActiveQuery;
use yii\db\Expression;

class PrizeQuery extends ActiveQuery
{

    public function random()
    {
        return $this->andWhere(['>', 'count', 0])
        ->orWhere(['like', 'type', Prize::TYPE_LOYALTY_POINTS])
        ->orderBy(new Expression('rand()'));
    }
}