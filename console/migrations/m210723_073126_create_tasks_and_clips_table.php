<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tasks_and_clips}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tasks}}`
 * - `{{%clips}}`
 */
class m210723_073126_create_tasks_and_clips_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tasks_and_clips}}', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull()->unsigned(),
            'clip_id' => $this->integer()->notNull()->unsigned(),
        ]);

        // creates index for column `task_id`
        $this->createIndex(
            '{{%idx-tasks_and_clips-task_id}}',
            '{{%tasks_and_clips}}',
            'task_id'
        );

        // add foreign key for table `{{%tasks}}`
        $this->addForeignKey(
            '{{%fk-tasks_and_clips-task_id}}',
            '{{%tasks_and_clips}}',
            'task_id',
            '{{%tasks}}',
            'id',
            'CASCADE'
        );

        // creates index for column `clip_id`
        $this->createIndex(
            '{{%idx-tasks_and_clips-clip_id}}',
            '{{%tasks_and_clips}}',
            'clip_id'
        );

        // add foreign key for table `{{%clips}}`
        $this->addForeignKey(
            '{{%fk-tasks_and_clips-clip_id}}',
            '{{%tasks_and_clips}}',
            'clip_id',
            '{{%clips}}',
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
            '{{%fk-tasks_and_clips-task_id}}',
            '{{%tasks_and_clips}}'
        );

        // drops index for column `task_id`
        $this->dropIndex(
            '{{%idx-tasks_and_clips-task_id}}',
            '{{%tasks_and_clips}}'
        );

        // drops foreign key for table `{{%clips}}`
        $this->dropForeignKey(
            '{{%fk-tasks_and_clips-clip_id}}',
            '{{%tasks_and_clips}}'
        );

        // drops index for column `clip_id`
        $this->dropIndex(
            '{{%idx-tasks_and_clips-clip_id}}',
            '{{%tasks_and_clips}}'
        );

        $this->dropTable('{{%tasks_and_clips}}');
    }
}
