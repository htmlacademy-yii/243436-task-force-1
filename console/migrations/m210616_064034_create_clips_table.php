<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%clips}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tasks}}`
 */
class m210616_064034_create_clips_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%clips}}', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull()->unsigned(),
            'path' => $this->string(100)->notNull(),
        ]);

        // creates index for column `task_id`
        $this->createIndex(
            '{{%idx-clips-task_id}}',
            '{{%clips}}',
            'task_id'
        );

        // add foreign key for table `{{%tasks}}`
        $this->addForeignKey(
            '{{%fk-clips-task_id}}',
            '{{%clips}}',
            'task_id',
            '{{%tasks}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%tasks}}`
        $this->dropForeignKey(
            '{{%fk-clips-task_id}}',
            '{{%clips}}'
        );

        // drops index for column `task_id`
        $this->dropIndex(
            '{{%idx-clips-task_id}}',
            '{{%clips}}'
        );

        $this->dropTable('{{%clips}}');
    }
}
