<?php

use yii\db\Migration;

/**
 * Handles the creation of table `chats`.
 */
class m200105_180809_create_chats_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('chats', [
            'id'         => $this->primaryKey()->unsigned(),
            'task_id'    => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_chats_tasks',
            'chats',
            'task_id',
            'tasks',
            'id',
            'NO ACTION',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_chats_tasks',
            'chats'
        );

        $this->dropTable('chats');
    }
}
