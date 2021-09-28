<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%messages}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tasks}}`
 */
class m210928_121338_add_task_id_column_to_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%messages}}', 'task_id', $this->integer()->notNull()->unsigned());

        // creates index for column `task_id`
        $this->createIndex(
            '{{%idx-messages-task_id}}',
            '{{%messages}}',
            'task_id'
        );

        // add foreign key for table `{{%tasks}}`
        $this->addForeignKey(
            '{{%fk-messages-task_id}}',
            '{{%messages}}',
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
            '{{%fk-messages-task_id}}',
            '{{%messages}}'
        );

        // drops index for column `task_id`
        $this->dropIndex(
            '{{%idx-messages-task_id}}',
            '{{%messages}}'
        );

        $this->dropColumn('{{%messages}}', 'task_id');
    }
}
