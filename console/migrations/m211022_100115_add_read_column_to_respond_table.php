<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%respond}}`.
 */
class m211022_100115_add_read_column_to_respond_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%respond}}', 'read', $this->string(10));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%respond}}', 'read');
    }
}
