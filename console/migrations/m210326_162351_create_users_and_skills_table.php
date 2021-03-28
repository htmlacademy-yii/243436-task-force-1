<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users_and_skills}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 * - `{{%skills}}`
 */
class m210326_162351_create_users_and_skills_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users_and_skills}}', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->notNull()->unsigned(),
            'skill_id' => $this->integer()->notNull()->unsigned(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-users_and_skills-user_id}}',
            '{{%users_and_skills}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-users_and_skills-user_id}}',
            '{{%users_and_skills}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `skill_id`
        $this->createIndex(
            '{{%idx-users_and_skills-skill_id}}',
            '{{%users_and_skills}}',
            'skill_id'
        );

        // add foreign key for table `{{%skills}}`
        $this->addForeignKey(
            '{{%fk-users_and_skills-skill_id}}',
            '{{%users_and_skills}}',
            'skill_id',
            '{{%skills}}',
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
            '{{%fk-users_and_skills-user_id}}',
            '{{%users_and_skills}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-users_and_skills-user_id}}',
            '{{%users_and_skills}}'
        );

        // drops foreign key for table `{{%skills}}`
        $this->dropForeignKey(
            '{{%fk-users_and_skills-skill_id}}',
            '{{%users_and_skills}}'
        );

        // drops index for column `skill_id`
        $this->dropIndex(
            '{{%idx-users_and_skills-skill_id}}',
            '{{%users_and_skills}}'
        );

        $this->dropTable('{{%users_and_skills}}');
    }
}
