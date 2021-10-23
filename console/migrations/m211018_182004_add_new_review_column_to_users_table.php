<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users}}`.
 */
class m211018_182004_add_new_review_column_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'new_review', $this->string(10));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'new_review');
    }
}
