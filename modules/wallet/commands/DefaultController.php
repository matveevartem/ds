<?php

namespace app\modules\wallet\commands;

use Yii;

use yii\console\Controller;
use yii\console\ExitCode;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;

//use app\components\QueueComponent;

use app\modules\wallet\models\Order;
use app\modules\wallet\models\WalletTransaction;
use app\modules\wallet\models\WalletTotal;


class DefaultController extends BaseController
{

    /*
     * QUEUE message reciever
     * Recieves messages from queue.
     * Makes records to the database.
     * 
     * @return void
     */
    public function actionIndex() : void
    {
        try {
            Yii::$app->getModule('wallet')->queue->recieve(function($msg) {
                $data = Json::decode($msg->body);
                
                $order = new Order;
                if( !$order->load($data) ) {
                    throw new \Exception(implode(', ', $order->errors));
                }
                if( !isset($data['signature']) || !$order->validator($data['signature']) ) {
                    $this->printError('AMQP message recieved', 'Invalid digital signature', 5 );
                }
                
                //Record one transaction
                $transaction = new WalletTransaction();
                $transaction->user_id = $order->order_id;
                $transaction->summ = $order->summ - $order->summ * $order->commision / 100;
                if( !$transaction->save() ) {
                    $this->printError('AMQP message recieved', 'Can not save user transaction summ', 6 );
                }

                //The sum of all the transaction amounts of the record
                if( !$total = WalletTotal::findOne($order->order_id) )
                {
                    $total = new WalletTotal();
                }
                $total->user_id = $order->order_id;
                $total->summ += $order->summ - $order->summ * $order->commision / 100;
                if( !$total->save() ) {
                    $this->printError('AMQP message recieved', 'Can not save user total summ', 7 );
                }
                $this->printSuccess('AMQP message received', $msg->body );
            });
        } catch(\PhpAmqpLib\Exception\AMQPIOException $e) {
            $this->printError('AMQP message could not be recieved', $e->getMessage(), 1, 500, $e);
        }
    }

}
