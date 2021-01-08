<?php

namespace app\modules\wallet\migrations;

/**
 * Class Migration
 */
class Migration extends \yii\db\Migration
{

    protected $strName;
    protected $tableName;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        preg_match('/.*\/modules\/(.+)\/migrations/', __DIR__, $matches);
        $this->tableName = '{{%'.$this->strName.'}}';
        $this->db = \Yii::$app->getModule($matches[1])->components['db'];
        parent::init();
    }

}
