<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message}}`.
 */
class m220817_091949_create_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%message}}', [
            'id' => $this->primaryKey(),
            'content' => $this->text()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'from_user_id' => $this->integer()->notNull(),
            'to_user_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('from_user_id', '{{%message}}', 'from_user_id');
        $this->createIndex('to_user_id', '{{%message}}', 'to_user_id');
        $this->addForeignKey(
            'fk_from_user_id', '{{%message}}', 'from_user_id',
            '{{%user}}', 'id',
            'RESTRICT', 'RESTRICT'
        );
        $this->addForeignKey(
            'fk_to_user_id', '{{%message}}', 'to_user_id',
            '{{%user}}', 'id',
            'RESTRICT', 'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%message}}');
    }
}
