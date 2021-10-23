<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tasks}}`.
 */
class m211022_063341_add_read_selected_executor_column_to_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tasks}}', 'read_selected_executor', $this->string(10));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tasks}}', 'read_selected_executor');
    }
}
