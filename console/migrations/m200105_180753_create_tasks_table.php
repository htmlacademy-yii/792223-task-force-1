<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tasks`.
 */
class m200105_180753_create_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tasks', [
            'id'          => $this->primaryKey()->unsigned(),
            'owner_id'    => $this->integer()->unsigned()->notNull(),
            'status'      => "ENUM ('new','cancelled','failed','in progress','completed','expired') NOT NULL",
            'agent_id'    => $this->integer()->unsigned()->null(),
            'name'        => $this->string(100)->notNull(),
            'description' => $this->text()->notNull(),
            'price'       => $this->integer()->unsigned()->notNull(),
            'expired_at'  => $this->dateTime()->notNull(),
            'category_id' => $this->integer()->unsigned()->notNull(),
            'location_id' => $this->integer()->unsigned()->null(),
            'created_at'  => $this->dateTime()->notNull(),
            'updated_at'  => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_tasks_locations',
            'tasks',
            'location_id',
            'locations',
            'id',
            'NO ACTION',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_tasks_categories',
            'tasks',
            'category_id',
            'task_categories',
            'id',
            'NO ACTION',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_tasks_users1',
            'tasks',
            'owner_id',
            'users',
            'id',
            'NO ACTION',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_tasks_users2',
            'tasks',
            'agent_id',
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
            'fk_tasks_locations',
            'tasks'
        );

        $this->dropForeignKey(
            'fk_tasks_categories',
            'tasks'
        );

        $this->dropForeignKey(
            'fk_tasks_users1',
            'tasks'
        );

        $this->dropForeignKey(
            'fk_tasks_users2',
            'tasks'
        );

        $this->dropTable('tasks');
    }
}
