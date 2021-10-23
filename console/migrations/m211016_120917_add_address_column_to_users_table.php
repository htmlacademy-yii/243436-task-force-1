<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users}}`.
 */
class m211016_120917_add_address_column_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'address', $this->string(700));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'address');
    }
}
