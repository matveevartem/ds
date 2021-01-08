<?php

namespace app\modules\atm\commands;

use Yii;

use yii\console\ExitCode;
use yii\httpclient\Client;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Console;

use app\modules\atm\models\Order;


class DefaultController extends BaseController
{

    public function actions()
    {
        return [];
        /*return [
            'error' => [
                'class' => 'components\ErrorException',
            ],
        ];*/
    }

    /*
     * Create random order.
     * Sends order data to the server
     * Works in loop.
     * 
     * @return void
     */
    public function actionHttp(int $interval = 0) : void
    {
        $interval = $interval ?: Yii::$app->params['delay'];

        Console::output();
        Console::output(
            \yii\helpers\Console::ansiFormat('Sending requests to ' . Yii::$app->params['wallet_api_url'],
            [Console::FG_CYAN, Console::BOLD] ));
        Console::output();

        while(true) {
            $order = new Order(true);
            if( !$order->save() ) {
                $this->printError('New order', 'Can not insert new order into the database', 2 );
                continue;
            }

            $msg = [
               (new \ReflectionClass(Order::class))->getShortName() => ArrayHelper::toArray($order),
               'signature' => $order->signer(),
            ];

            try {
                $client = new Client();
                $response = $client->createRequest()
                    ->setMethod('post')
                    ->setFormat(Client::FORMAT_JSON)
                    ->setUrl(Yii::$app->params['wallet_api_url'] . '?suppress_response_code=1')
                    ->setData($msg)
                    ->send();
            } catch(\yii\httpclient\Exception $e) {
                $this->printError('Transport error', $e->getMessage(), $e->getCode() );
                $response = null;
            }

            if ($response && $response->isOk) {
                if( isset($response->data['success']) && $response->data['success'] ) {
                    $this->printSuccess('POST request sent', 
                        '[To:] Sent ' . Json::encode($msg) . "\n" .
                        '[From:] transaction_id ' . $order->id . ', ' . $this->arrayToString($response->data['data'])
                    );
                } else {
                    $order->delete();
                }
            } else {
                $order->delete();
            }

            sleep($interval);
        }
    }

    /*
     * Create random order.
     * Sends order data to the server.
     * Works in loop.
     * 
     * @return void
     */
    public function actionQueue(int $interval = 0) : void
    {
        $interval = $interval ?: Yii::$app->params['delay'];
        while(true) {
            $order = new Order(true);
            if( !$order->save() ) {
                $this->printError('New order', 'Can not insert new order into the database', 2 );
                continue;
            }

            $msg = Json::encode([
                   (new \ReflectionClass(Order::class))->getShortName() => ArrayHelper::toArray($order),
                   'signature' => $order->signer(),
            ]);

            try {
                Yii::$app->getModule('atm')->queue->send($msg);
                $this->printSuccess('AMQP message was sent', $msg );
            } catch ( \PhpAmqpLib\Exception\AMQPIOException $e) { 
                $order->delete();
                $this->printError('AMQP message not sent', $e->getMessage(), 1, 500, $e);
            }

            sleep($interval);
        }
    }

}
