<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%replies}}`.
 */
class m210928_125854_drop_replies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%replies}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%replies}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}
