<?php
 
namespace app\modules\wallet\components;
 
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

use yii\console\ExitCode;
use yii\helpers\Console;


class QueueComponent extends Component {

    //Default params values
    const HOST = 'localhost';
    const PORT = 5672;
    const USER = 'guest';
    const PAWWORD = 'guest';

    private $queue;
    private $conn;
    private $queueName;

    public function __construct($config = [])
    {
        if( !isset($config['queueName']) ) {
            die("QueueComponent needs [queueName] parametre\n");
        }

        $host = $config['host'] ?? self::HOST;
        $port = $config['port'] ?? self::PORT;
        $user = $config['user'] ?? self::USER;
        $password = $config['password'] ?? self::PASSWORD;

        $this->queueName = $config['queueName'];
        
        try {
            $this->conn = new AMQPStreamConnection($host, $port, $user, $password);
            $this->queue = $this->conn->channel();
            $this->queue->queue_declare($this->queueName, false, false, false, false);
            Console::output();
            Console::output(
                Console::ansiFormat('Connected to AMQP service',
                [Console::FG_CYAN, Console::BOLD] ));
            Console::output();
        } catch(\Exception $e) {
            throw new \PhpAmqpLib\Exception\AMQPIOException($e->getMessage(), $e->getCode());
        }
    }

    public function __destruct()
    {
        try {
            $this->queue->close();
            $this->conn->close();
        } catch(\Error $e) { }
    }

    /*
     * Send message to the queue.
     */
    public function send(string $msg)
    {
        $this->queue->basic_publish(new AMQPMessage($msg), '', $this->queueName);
    }

    /*
     * Recieve message from queue.
     */
    public function recieve($callback)
    {
        $this->queue->basic_consume($this->queueName, '', false, true, false, false, $callback);
        while ($this->queue->is_consuming()) {
            $this->queue->wait();
        }
    }

}
