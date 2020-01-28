<?php


namespace app\modules\api\v1\controllers;

use app\modules\api\v1\models\BankAccount;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\Response;

/**
 * Country Controller API
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class BankController extends ActiveController
{
    public $modelClass = BankAccount::class;

    public function behaviors()
    {
        return [
            [
                'class' => ContentNegotiator::class,
                'formats' => ['application/json' => Response::FORMAT_JSON],
            ],
        ];
    }

    public function actions()
    {
        return [
            'view' => [
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'update' => [
                'class' => 'app\modules\api\v1\controllers\actions\BankAccountUpdateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
        ];
    }


}