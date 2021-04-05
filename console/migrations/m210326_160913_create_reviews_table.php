<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reviews}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 * - `{{%users}}`
 */
class m210326_160913_create_reviews_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reviews}}', [
            'id' => $this->primaryKey()->unsigned(),
            'review' => $this->text(800)->notNull(),
            'rating' => $this->integer()->notNull()->unsigned(),
            'user_id_create' => $this->integer()->notNull()->unsigned(),
            'user_id_executor' => $this->integer()->notNull()->unsigned(),
        ]);

        // creates index for column `user_id_create`
        $this->createIndex(
            '{{%idx-reviews-user_id_create}}',
            '{{%reviews}}',
            'user_id_create'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-reviews-user_id_create}}',
            '{{%reviews}}',
            'user_id_create',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id_executor`
        $this->createIndex(
            '{{%idx-reviews-user_id_executor}}',
            '{{%reviews}}',
            'user_id_executor'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-reviews-user_id_executor}}',
            '{{%reviews}}',
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
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-reviews-user_id_create}}',
            '{{%reviews}}'
        );

        // drops index for column `user_id_create`
        $this->dropIndex(
            '{{%idx-reviews-user_id_create}}',
            '{{%reviews}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-reviews-user_id_executor}}',
            '{{%reviews}}'
        );

        // drops index for column `user_id_executor`
        $this->dropIndex(
            '{{%idx-reviews-user_id_executor}}',
            '{{%reviews}}'
        );

        $this->dropTable('{{%reviews}}');
    }
}
