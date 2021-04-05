<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%skills}}`.
 */
class m210326_161446_create_skills_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%skills}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->char()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%skills}}');
    }
}
