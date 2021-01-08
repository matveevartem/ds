<?php

namespace app\modules\wallet\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

use app\modules\wallet\models\Order;
use app\modules\wallet\models\WalletTransaction;
use app\modules\wallet\models\WalletTotal;

class DefaultController extends ActiveController
{

    public $modelClass = 'app\modules\wallet\models\Order';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
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
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            /*'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],*/
        ];
    }

    public function beforeAction($action) 
    {

        \Yii::$app->getResponse()
            ->getHeaders()
            ->set('Access-Control-Allow-Origin', '*');
        
        //$this->enableCsrfValidation = false;
        
        return parent::beforeAction($action);

    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        throw new \yii\web\MethodNotAllowedHttpException('index');
    }

    /**
     * @return mixed
     */
    public function actionView()
    {
        throw new \yii\web\MethodNotAllowedHttpException('view');
    }

    /**
     * @return mixed
     */
    public function actionUpdate()
    {
        throw new \yii\web\MethodNotAllowedHttpException('update');
    }

    /**
     * @return mixed
     */
    public function actionDelete()
    {
        throw new \yii\web\MethodNotAllowedHttpException('delete');
    }

    /**
     * @return mixed
     */
    public function actionOptions()
    {
        throw new \yii\web\MethodNotAllowedHttpException('options');
    }

    /**
     * Recieves post request.
     * Makes records to the database.
     * 
     * @return array
     */
    public function actionCreate() : array
    {
        $order = new Order;
        if( !$order->load(Yii::$app->request->post()) ) {
            throw new \yii\web\BadRequestHttpException(implode(', ', $order->errors));
        }
        if( !$order->validator(Yii::$app->request->post('signature')) ) {
            throw new \yii\web\BadRequestHttpException('Invalid digital signature');
        }
        
        //Record one transaction
        $transaction = new WalletTransaction();
        $transaction->user_id = $order->order_id;
        $transaction->summ = $order->summ - $order->summ * $order->commision / 100;
        if( !$transaction->save() ) {
            throw new \yii\web\ServerErrorHttpException('Can not save user transaction summ');
        }

        //The sum of all the transaction amounts of the record
        if( !$total = WalletTotal::findOne($order->order_id) )
        {
            $total = new WalletTotal();
        }
        $total->user_id = $order->order_id;
        $total->summ += $order->summ - $order->summ * $order->commision / 100;
        if( !$total->save() ) {
            throw new \yii\web\ServerErrorHttpException('Can not save user total summ');
        }

        return [
            'user_id' => $order->order_id,
            'current_summ' => $transaction->summ,
            'total_summ' => $total->summ,
        ];
    }

}
