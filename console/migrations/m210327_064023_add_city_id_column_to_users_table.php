<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cities}}`
 */
class m210327_064023_add_city_id_column_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'city_id', $this->integer()->unsigned());

        // creates index for column `city_id`
        $this->createIndex(
            '{{%idx-users-city_id}}',
            '{{%users}}',
            'city_id'
        );

        // add foreign key for table `{{%cities}}`
        $this->addForeignKey(
            '{{%fk-users-city_id}}',
            '{{%users}}',
            'city_id',
            '{{%cities}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%cities}}`
        $this->dropForeignKey(
            '{{%fk-users-city_id}}',
            '{{%users}}'
        );

        // drops index for column `city_id`
        $this->dropIndex(
            '{{%idx-users-city_id}}',
            '{{%users}}'
        );

        $this->dropColumn('{{%users}}', 'city_id');
    }
}
