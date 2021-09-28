<?php

use yii\db\Migration;

/**
 * Class m210928_114857_rename_city_column_to_cities_table_in_name_column
 */
class m210928_114857_rename_city_column_to_cities_table_in_name_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%cities}}', 'city', 'name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%cities}}', 'name');
    }
}
