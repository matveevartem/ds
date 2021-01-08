<?php

namespace app\modules\wallet\models;

use Yii;

/**
 * This is the model class for table "user_wallet".
 * Stores a list of summ of all recived transactions for each users.
 *
 * @property int $id
 * @property int $user_id
 * @property int $summ
 * @property string $created_at
 */
class WalletTransaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_wallet';
    }

    /**
     * {@inheritdoc}
     */
    public static function getDb()
    {
        return Yii::$app->getModule('wallet')->get('db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'summ'], 'required'],
            [['user_id'], 'integer'],
            [['summ'], 'number'],
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
            'user_id' => 'User ID',
            'summ' => 'Summ',
            'created_at' => 'Created At',
        ];
    }
}
