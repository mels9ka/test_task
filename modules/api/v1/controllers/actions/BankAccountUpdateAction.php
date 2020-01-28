<?php

namespace app\modules\api\v1\controllers\actions;

use app\modules\api\v1\models\BankAccount;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;

class BankAccountUpdateAction extends Action
{
    /**
     * @var string the scenario to be assigned to the model before it is validated and updated.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * Updates an BankAccount model.
     * @param string $id the primary key of the model.
     * @return BankAccount the model being updated
     * @throws ServerErrorHttpException if there is any error when updating the model
     * @throws InvalidConfigException if a registered parser does not implement the [[RequestParserInterface]].
     */
    public function run($id)
    {
        $moneyField = 'money';
        $bodyParams = \Yii::$app->getRequest()->getBodyParams();

        if(!array_key_exists($moneyField, $bodyParams))
            throw new ServerErrorHttpException('Wrong body params.');

        /* @var $model BankAccount */
        $model = BankAccount::findOne($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        $model->scenario = $this->scenario;
        $oldMoney = $model->money;
        $model->load($bodyParams, '');
        $model->money += $oldMoney;

        if ($model->save() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }

        return $model;
    }
}