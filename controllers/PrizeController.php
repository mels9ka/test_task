<?php

namespace app\controllers;

use app\logic\MoneySender;
use app\logic\PrizeMaker;
use app\logic\PrizeOrderManager;
use app\models\PrizeOrder;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class PrizeController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['get-prize', 'accept-prize', 'decline-prize'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }



    public function actionGetPrize()
    {
        if (!Yii::$app->user->isGuest && Yii::$app->request->isAjax)
        {
            $prizeMaker = new PrizeMaker(Yii::$app->user->id);
            $prizeOrder = $prizeMaker->makePrize();
            $prizeOrderManager = new PrizeOrderManager($prizeOrder);
            return $prizeOrderManager->makeJsonResponseForNewOrder();
        }

        return Json::encode(['msg' => 'not allowed']);
    }

    public function actionAcceptPrize()
    {
        if (!Yii::$app->user->isGuest
            && Yii::$app->request->isPost
            && Yii::$app->request->isAjax)
        {
            $id = Yii::$app->request->post('id');
            $isConvert = Yii::$app->request->post('is_convert');
            $prizeOrder = PrizeOrder::findOne($id);
            $prizeOrder->is_convert = $isConvert;

            $prizeOrderManager = new PrizeOrderManager($prizeOrder);
            $isAccepted = $prizeOrderManager->acceptOrder();

            return Json::encode(['result' => $isAccepted]);
        }

        return Json::encode(['msg' => 'not allowed']);
    }

    public function actionDeclinePrize()
    {
        if (!Yii::$app->user->isGuest
            && Yii::$app->request->isPost
            && Yii::$app->request->isAjax)
        {
            $id = Yii::$app->request->post('id');
            $prizeOrder = PrizeOrder::findOne($id);

            $prizeOrderManager = new PrizeOrderManager($prizeOrder);
            $isDeclined = $prizeOrderManager->declineOrder();

            return Json::encode(['result' => $isDeclined]);
        }

        return Json::encode(['msg' => 'not allowed']);
    }
}
