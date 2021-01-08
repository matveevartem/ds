<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

#use app\modules\atm\models\Order;
#use app\modules\wallet\models\WalletTransaction;
#use app\modules\wallet\models\WalletTotal;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if(Yii::$app->hasModule('wallet')) {
            $module = Yii::$app->getModule('wallet');
        }
        return $this->render('index', [
            'orders' => Yii::$app->hasModule('atm') ? \app\modules\atm\models\Order::find()->all() : [],
            'transactions' => Yii::$app->hasModule('wallet') ? \app\modules\wallet\models\WalletTransaction::find()->all() : [],
            'totals' => Yii::$app->hasModule('wallet') ? \app\modules\wallet\models\WalletTotal::find()->all() : [],
        ]);
    }


}
