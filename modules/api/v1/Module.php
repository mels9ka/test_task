<?php


namespace app\modules\api\v1;


use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\api\v1\controllers';

    public function bootstrap($app)
    {
        $app->urlManager->addRules([
            [
                'class' => 'yii\rest\UrlRule',
                'controller' => ['bank'],
            ],

        ]);
    }
}