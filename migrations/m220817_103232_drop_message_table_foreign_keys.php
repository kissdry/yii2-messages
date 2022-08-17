<?php

use yii\db\Migration;

/**
 * Class m220817_103232_drop_message_table_foreign_keys
 */
class m220817_103232_drop_message_table_foreign_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk_from_user_id', '{{%message}}');
        $this->dropForeignKey('fk_to_user_id', '{{%message}}');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220817_103232_drop_message_table_foreign_keys cannot be reverted.\n";

        return false;
    }
}
