<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%respond}}`.
 */
class m210928_123600_add_status_column_to_respond_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%respond}}', 'status', $this->string(15));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%respond}}', 'status');
    }
}
