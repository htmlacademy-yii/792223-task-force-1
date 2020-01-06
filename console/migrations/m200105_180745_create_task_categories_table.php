<?php

use yii\db\Migration;

/**
 * Handles the creation of table `task_categories`.
 */
class m200105_180745_create_task_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('task_categories', [
            'id'         => $this->primaryKey()->unsigned(),
            'name'       => $this->string(45)->notNull()->unique(),
            'slug'       => $this->string(45)->notNull()->unique(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('task_categories');
    }
}
