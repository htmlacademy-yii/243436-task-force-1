<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%respond}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tasks}}`
 * - `{{%users}}`
 */
class m210616_061756_create_respond_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%respond}}', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull()->unsigned(),
            'user_id_executor' => $this->integer()->notNull()->unsigned(),
            'comment' => $this->text(),
            'date' => $this->datetime(),
            'budget' => $this->integer(),
        ]);

        // creates index for column `task_id`
        $this->createIndex(
            '{{%idx-respond-task_id}}',
            '{{%respond}}',
            'task_id'
        );

        // add foreign key for table `{{%tasks}}`
        $this->addForeignKey(
            '{{%fk-respond-task_id}}',
            '{{%respond}}',
            'task_id',
            '{{%tasks}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id_executor`
        $this->createIndex(
            '{{%idx-respond-user_id_executor}}',
            '{{%respond}}',
            'user_id_executor'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-respond-user_id_executor}}',
            '{{%respond}}',
            'user_id_executor',
            '{{%users}}',
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
            '{{%fk-respond-task_id}}',
            '{{%respond}}'
        );

        // drops index for column `task_id`
        $this->dropIndex(
            '{{%idx-respond-task_id}}',
            '{{%respond}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-respond-user_id_executor}}',
            '{{%respond}}'
        );

        // drops index for column `user_id_executor`
        $this->dropIndex(
            '{{%idx-respond-user_id_executor}}',
            '{{%respond}}'
        );

        $this->dropTable('{{%respond}}');
    }
}
