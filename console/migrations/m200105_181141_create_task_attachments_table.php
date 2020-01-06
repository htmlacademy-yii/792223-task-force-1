<?php

use yii\db\Migration;

/**
 * Handles the creation of table `task_attachments`.
 */
class m200105_181141_create_task_attachments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('task_attachments', [
            'id'         => $this->primaryKey()->unsigned(),
            'task_id'    => $this->integer()->unsigned()->notNull(),
            'name'       => $this->string(100)->notNull(),
            'extension'  => $this->string(45)->notNull(),
            'mime'       => $this->string(45)->notNull(),
            'size'       => $this->integer()->unsigned()->notNull(),
            'path'       => $this->string(100)->notNull(),
            'hash'       => $this->string(100)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_task_attachments_tasks',
            'task_attachments',
            'task_id',
            'tasks',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_task_attachments_tasks',
            'task_attachments'
        );

        $this->dropTable('task_attachments');
    }
}
