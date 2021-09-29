<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%reviews}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tasks}}`
 */
class m210621_150600_add_task_id_column_to_reviews_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%reviews}}', 'task_id', $this->integer()->unsigned());

        // creates index for column `task_id`
        $this->createIndex(
            '{{%idx-reviews-task_id}}',
            '{{%reviews}}',
            'task_id'
        );

        // add foreign key for table `{{%tasks}}`
        $this->addForeignKey(
            '{{%fk-reviews-task_id}}',
            '{{%reviews}}',
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
            '{{%fk-reviews-task_id}}',
            '{{%reviews}}'
        );

        // drops index for column `task_id`
        $this->dropIndex(
            '{{%idx-reviews-task_id}}',
            '{{%reviews}}'
        );

        $this->dropColumn('{{%reviews}}', 'task_id');
    }
}
