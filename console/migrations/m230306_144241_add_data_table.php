<?php

use yii\db\Migration;

/**
 * Class m230306_144241_add_data_table
 */
class m230306_144241_add_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%data}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'data' => $this->text()->null(),            
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()->null(),
            'deleted_at' => $this->integer()->null(),
        ]);
		$this->addForeignKey('fk-data-user', 'data', 'user_id', 'user', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropTable('{{%data}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230306_144241_add_data_table cannot be reverted.\n";

        return false;
    }
    */
}
