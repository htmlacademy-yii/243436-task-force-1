<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%opinions}}`.
 */
class m210928_130118_drop_opinions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%opinions}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%opinions}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}
