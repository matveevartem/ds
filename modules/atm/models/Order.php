<?php


namespace app\modules\atm\models;

use Yii;

/**
 * This is the model class for table "order".
 * Stores a list of sent transactions for each users.
 *
 * @property int $id
 * @property int $order_id
 * @property int $summ
 * @property float $commision
 * @property string $created_at
 */
class Order extends \yii\db\ActiveRecord
{
    private $sign;
    private $generate;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public static function getDb()
    {
        return Yii::$app->getModule('atm')->get('db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'summ', 'commision'], 'required'],
            [['order_id', 'summ'], 'integer'],
            [['commision'], 'number'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'summ' => 'Summ',
            'commision' => 'Commision',
            'created_at' => 'Created At',
        ];
    }

    /*
     * Setting $this->generate variable
     */
    public function __construct($generate = false)
    {
        $this->generate = $generate;
        parent::__construct();
    }

    /*
     * if $this->generate == true then random data will be generated.
     */
    public function init()
    {
        parent::init();
        if($this->generate) {
            $rand_func = function_exists('mt_rand') ? 'mt_rand' : 'rand'; //select random function
            $this->order_id = $rand_func(1, 20); //generate order(user) id
            $this->summ = $rand_func(10, 500); //generate summ
            $this->commision = $rand_func(50, 200) / 100; //generate commision
        }
    }

    /*
     * Return order's digital signature.
     * 
     * @return string
     */
    public function signer() : string
    {
        return md5(Yii::$app->params['salt'] . '00' .
            $this->order_id . '00' .
            $this->summ . '00' .
            $this->commision . '00' .
            $this->created_at);
    }

    /*
     * Validate digital signature.
     * 
     * @return bool
     */
    public function validator(string $sign) : bool
    {
        return !boolval(strcmp($this->signer(), $sign));
    }

}
