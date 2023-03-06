<?php

use yii\db\Migration;

/**
 * Class m230306_135822_add_token_table
 */
class m230306_135822_add_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->createTable('{{%token}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'code' => $this->string(32)->notNull()->unique()->comment('code of token'),
            'type' => $this->smallInteger(4)->notNull()->comment('type of token'),
            'created_at' => $this->integer()->notNull()
        ]);

        $this->createIndex('token_unique_index', 'token', 'user_id, code, type', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('token_unique_index', 'token');
        $this->dropTable('{{%token}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230306_135822_add_token_table cannot be reverted.\n";

        return false;
    }
    */
}
