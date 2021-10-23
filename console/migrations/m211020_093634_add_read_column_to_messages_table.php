<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%messages}}`.
 */
class m211020_093634_add_read_column_to_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%messages}}', 'read', $this->string(10));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%messages}}', 'read');
    }
}
