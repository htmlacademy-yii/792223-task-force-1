<?php

use yii\db\Migration;

/**
 * Handles the creation of table `task_applications`.
 */
class m200105_181122_create_task_applications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('task_applications', [
            'id'          => $this->primaryKey(),
            'task_id'     => $this->integer()->unsigned()->notNull(),
            'user_id'     => $this->integer()->unsigned()->notNull(),
            'price'       => $this->integer()->unsigned()->notNull(),
            'comment'     => $this->string(500)->notNull(),
            'is_rejected' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
            'created_at'  => $this->dateTime()->notNull(),
            'updated_at'  => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_task_applications_tasks',
            'task_applications',
            'task_id',
            'tasks',
            'id',
            'NO ACTION',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_task_applications_users',
            'task_applications',
            'user_id',
            'users',
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
            'fk_task_applications_tasks',
            'task_applications'
        );

        $this->dropForeignKey(
            'fk_task_applications_users',
            'task_applications'
        );

        $this->dropTable('task_applications');
    }
}
