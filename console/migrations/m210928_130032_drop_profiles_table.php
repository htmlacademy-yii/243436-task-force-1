<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%profiles}}`.
 */
class m210928_130032_drop_profiles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%profiles}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%profiles}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}
