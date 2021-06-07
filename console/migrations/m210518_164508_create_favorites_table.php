<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%favorites}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 * - `{{%users}}`
 */
class m210518_164508_create_favorites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%favorites}}', [
            'id' => $this->primaryKey(),
            'user_id_create' => $this->integer()->notNull()->unsigned(),
            'user_id_executor' => $this->integer()->notNull()->unsigned(),
        ]);

        // creates index for column `user_id_create`
        $this->createIndex(
            '{{%idx-favorites-user_id_create}}',
            '{{%favorites}}',
            'user_id_create'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-favorites-user_id_create}}',
            '{{%favorites}}',
            'user_id_create',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id_executor`
        $this->createIndex(
            '{{%idx-favorites-user_id_executor}}',
            '{{%favorites}}',
            'user_id_executor'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-favorites-user_id_executor}}',
            '{{%favorites}}',
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
            '{{%fk-favorites-user_id_create}}',
            '{{%favorites}}'
        );

        // drops index for column `user_id_create`
        $this->dropIndex(
            '{{%idx-favorites-user_id_create}}',
            '{{%favorites}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-favorites-user_id_executor}}',
            '{{%favorites}}'
        );

        // drops index for column `user_id_executor`
        $this->dropIndex(
            '{{%idx-favorites-user_id_executor}}',
            '{{%favorites}}'
        );

        $this->dropTable('{{%favorites}}');
    }
}
