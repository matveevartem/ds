<?php

namespace app\modules\wallet;

use Yii;
use \yii\di\ServiceLocator;

use yii\base\BootstrapInterface;
use yii\base\Application;


class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $app->getResponse()->on('beforeSend', function($event){
            $response = $event->sender;
            if ($response->data !== null && Yii::$app->request->get('suppress_response_code') ) {
                $response->data = [
                    'success' => $response->isSuccessful,
                    'data' => $response->data,
                ];
                $response->statusCode = 200;
            }
        });

        $app->getUrlManager()->addRules([
            [
                'class' => 'yii\rest\UrlRule', 
                'pluralize' => false,
                'controller' => ['wallet/default'],
            ],
        ]);
    }

}
