<?php

use yii\db\Migration;

/**
 * Handles the creation of table `task_reviews`.
 */
class m200105_181156_create_task_reviews_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('task_reviews', [
            'id'           => $this->primaryKey()->unsigned(),
            'task_id'      => $this->integer()->unsigned()->notNull(),
            'is_completed' => $this->tinyInteger()->unsigned()->notNull(),
            'rating'       => $this->tinyInteger()->unsigned()->null(),
            'comment'      => $this->string(500)->notNull(),
            'created_at'   => $this->dateTime()->notNull(),
            'updated_at'   => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_task_reviews_tasks',
            'task_reviews',
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
            'fk_task_reviews_tasks',
            'task_reviews'
        );

        $this->dropTable('task_reviews');
    }
}
