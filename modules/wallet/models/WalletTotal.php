<?php

namespace app\modules\wallet\models;

use Yii;

/**
 * This is the model class for table "user_total".
 * Stores a list of recived transactions for each users.
 *
 * @property int $id
 * @property int $user_id
 * @property float $summ
 * @property string $updated_at
 */
class WalletTotal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_total';
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
            [['updated_at'], 'safe'],
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
            'updated_at' => 'Updated At',
        ];
    }
}
