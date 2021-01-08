<?php

use yii\db\Migration;

/**
 * Class m210107_113760_order
 */
class m210107_113760_order extends app\modules\wallet\migrations\Migration
{
    protected $strName = 'order';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if (\Yii::$app->getModule('wallet')->db->schema->getTableSchema($this->tableName, true) === null) {
        
            $this->execute("DROP TABLE IF EXISTS {$this->tableName}");
            
            $tableOptions = null;
            /* Если mysql то устанавливаем кодировку */
            if ($this->db->driverName === 'mysql') {
                $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
            }

            /* Создаем таблицу */
            $this->createTable($this->tableName, [
                'id' => $this->primaryKey(),
                'order_id' => $this->tinyInteger(1)->notNull(),
                'summ' => $this->tinyInteger(1)->notNull(),
                'commision' => $this->float(1,2)->notNull(),
                'created_at' => $this->dateTime()->defaultValue(new \yii\db\Expression("CURRENT_TIMESTAMP"))->notNull(),
                
            ], $tableOptions);

            /* Создаем индексы */
            $this->createIndex('order_id', $this->tableName, 'order_id');
        } else {
            $this->truncateTable($this->tableName);
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201225_013759_order cannot be reverted.\n";

        return false;
    }
    */
}
