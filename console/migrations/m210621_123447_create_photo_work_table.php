<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%photo_work}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 */
class m210621_123447_create_photo_work_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%photo_work}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->unsigned(),
            'path' => $this->string(100),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-photo_work-user_id}}',
            '{{%photo_work}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-photo_work-user_id}}',
            '{{%photo_work}}',
            'user_id',
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
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-photo_work-user_id}}',
            '{{%photo_work}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-photo_work-user_id}}',
            '{{%photo_work}}'
        );

        $this->dropTable('{{%photo_work}}');
    }
}
