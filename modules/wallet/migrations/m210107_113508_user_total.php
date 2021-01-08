<?php

/**
 * Class m210107_113508_user_total
 */
class m210107_113508_user_total extends app\modules\wallet\migrations\Migration
{
    protected $strName = 'user_total';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("DROP TABLE IF EXISTS {$this->tableName}");
        
        $tableOptions = null;
        /* Если mysql то устанавливаем кодировку */
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        /* Создаем таблицу */
        $this->createTable($this->tableName, [
            'user_id' => $this->primaryKey(1),
            'summ' => $this->float(4,2)->notNull(),
            'updated_at' => $this->dateTime()->defaultValue(new \yii\db\Expression('CURRENT_TIMESTAMP'))->notNull(),

        ], $tableOptions);

        /* Создаем индексы */
        $this->createIndex('t_user_id', $this->tableName, 'user_id');

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
        echo "m201225_023508_user_total cannot be reverted.\n";

        return false;
    }
    */
}
