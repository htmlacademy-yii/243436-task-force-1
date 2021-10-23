<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users_and_categories}}`.
 */
class m211016_185316_add_id_column_to_users_and_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users_and_categories}}', 'id', $this->primaryKey());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users_and_categories}}', 'id');
    }
}
