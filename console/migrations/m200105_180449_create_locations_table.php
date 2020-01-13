<?php

use yii\db\Migration;

/**
 * Handles the creation of table `locations`.
 */
class m200105_180449_create_locations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('locations', [
            'id'        => $this->primaryKey()->unsigned(),
            'city'      => $this->string(100)->notNull(),
            'latitude'  => $this->double()->null(),
            'longitude' => $this->double()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('locations');
    }
}
